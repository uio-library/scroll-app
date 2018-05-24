<?php

namespace App\Http\Controllers;

use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Http\Request;
use App\Course;
use Carbon\Carbon;
use Psy\Exception\ErrorException;

class CourseController extends Controller
{

    /**
     * Display a list of courses.
     */
    public function index(Request $request)
    {
        return view('courses.index', ['courses' => Course::get()]);
    }

    /**
     * Display the contents of a single course.
     */
    public function show(Course $course)
    {
        return view('courses.show', ['course' => $course]);
    }

    /**
     * Get an image or other resource file part of a course.
     */
    public function resource(Course $course, $filename)
    {
        // Remove .. and /
        $filename = preg_replace('/(?:\.\.|\/)/', '', $filename);

        $path = storage_path("app/public/{$course->id}/{$filename}");

        if (!is_file($path)) {
            return response('Not found', 404);
        }

        return response()->file($path);
    }

    /**
     * Show course settings. Not yet used for anything.
     */
    public function settings(Course $course)
    {
        return view('courses.settings', ['course' => $course]);
    }

    /**
     * Register a new hook for the GitHub push event for a given course.
     * This requires that a GitHub integration has been set up.
     */
    public function createGithubHook(Request $request, Course $course)
    {
        $user = $request->user();
        if (is_null($user->getIntegration('github'))) {
            die('No github integration setup');
        }
        $github = $user->getGithubManager();

        list($org, $name) =  explode('/', $course->repo);

        // Note this will throw Github\Exception\RuntimeException if we request a repo we don't have permissions to
        $response = $github->repos()->hooks()->all($org, $name);
        // TODO: check if there is already a hook.

        $callback_url = action('CourseController@githubHookCallback', ['course' => $course->name]);

        // echo $callback_url;die;
        $secret = str_random(30);
        $params = [
            'name' => 'web',
            'events' => ['push'],
            'config' => [
                'content_type' => 'json',
                'url' => $callback_url,
                'secret' => $secret,
            ],
        ];
        $response = $github->repos()->hooks()->create($org, $name, $params);

        $course->github_secret = $secret;
        $course->github_hook = $response;
        $course->save();

        return back()->with('status', 'Hook created!');
    }

    /**
     * Test an existing GitHub hook. This will cause GitHub to
     * call our callback URI in the same way as with a real push.
     */
    public function testGithubHook(Request $request, Course $course)
    {
        $user = $request->user();
        if (is_null($user->getIntegration('github'))) {
            die('No github integration setup');
        }
        $github = $user->getGithubManager();

        $hook = $course->github_hook;

        if (is_null($hook)) {
            die('No github hook found for this repo');
        }

        list($org, $name) =  explode('/', $course->repo);

        $github->repos()->hooks()->test($org, $name, $hook->id);

        return back()->with('status', 'Test sent. Reload the page in a few seconds ' .
            'to check if the event was received.');
    }

    /**
     * Listener for the GitHub push webhook.
     */
    public function githubHookCallback(Request $request, Course $course)
    {
        $eventType = $request->header('X-GitHub-Event');
        $sig = $request->header('X-Hub-Signature');

        $sig2 = 'sha1=' . hash_hmac('sha1', $request->getContent(), $course->github_secret);

        if ($sig !== $sig2) {
            \Log::warning("Ignoring GitHub '{$eventType}' event for course {$course->name} due to ' .
                'invalid signature. This could be due to duplicate webhooks or just an malicious request.");
            return response('Signature not recognized. Please delete and re-register webhook.', 202);
        }

        \Log::info("Received GitHub '{$eventType}' event for course {$course->name}");
        // \Log::info(json_encode($request->all()));

        $course->last_event_at = Carbon::now();
        $course->last_event_type = $eventType;
        $course->last_event = $request->all();
        $course->save();

        if ($eventType == 'ping') {
            return response('pong');
        } elseif ($eventType != 'push') {
            return response('Unknown event type.', 202);
        }

        if ($request->input('ref') != 'refs/heads/master') {
            return response('Push was not to master.', 202);
        }

        // Pull in the changes
        \Artisan::call('course:pull', [
            'course' => $course->name,
        ]);

        // Then import the course
        \Artisan::call('course:load', [
            'course' => $course->name,
        ]);

        // Say thanks to GitHub
        return response('Thanks', 200);
    }

    public function new(Request $request)
    {
        return view('courses.new', []);
    }

    protected function getTree($github, $user, $name, $sha, $tree = [], $path = '')
    {
        $response = $github->git()->trees()->show($user, $name, $sha);

        foreach ($response['tree'] as $obj) {
            if ($obj['type'] == 'blob') {
                $obj['full_path'] = $path . $obj['path'];
                $tree[] = $obj;
            } elseif ($obj['type'] == 'tree') {
                $tree = $this->getTree($github, $user, $name, $obj['sha'], $tree, $path . $obj['path'] . '/');
            }
        }

        return $tree;
    }

    public function saveNew(Request $request, GitHubManager $g)
    {

//        $g->git()->trees()
        $validator = \Validator::make([], []);

        if (count(explode('/', $request->repo)) != 2) {
            $validator->getMessageBag()->add('repo', 'Reponavn må oppgis på formen org/repo');

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        list($user, $name) = explode('/', $request->repo);

        $github = $request->user()->getGithubManager();
        try {
            $repo = $github->repo()->show($user, $name);
        } catch (\RuntimeException $e) {
            $validator->getMessageBag()->add('repo', 'Fant ikke repoet');

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $courseData = [
            'name' => $repo['name'],
            'repo' => $repo['full_name'],
            'modules' => new \stdClass(),
            'header' => '',
            'headertext' => '',
        ];

        $validator = \Validator::make($courseData, [
            'name' => 'required|unique:courses|max:255',
            'repo' => 'required|unique:courses|max:255',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $github->repos()->hooks();

        // $hooks = $github->repos()->hooks()->all($user, $name);
        // TODO: Add hook

//        $refs = $github->git()->references()->all($user, $name);
//        return response()->json($refs);

        $ref = $github->git()->references()->show($user, $name, 'heads/master');
        $refSha = array_get($ref, 'object.sha');

        $tree = $this->getTree($github, $user, $name, $refSha);

//        $blob = $github->git()->blobs()->show($user, $name, $tree[0]['sha']);
//        $data = base64_decode($blob['content']);

        return response()->json($tree);


        Course::create($courseData);

        \Session::flash('status', 'Kurset ble opprettet');
        return response()->redirectTo('/');
    }
}

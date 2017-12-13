<?php

namespace App\Http\Controllers;

use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Http\Request;
use \App\Course;
use Psy\Exception\ErrorException;

class CourseController extends Controller
{
    public function index(Request $request) {
        return view('courses.index', ['courses' => Course::get()]);
    }

    public function show(Request $request, $name) {
		$course = Course::where(['name' => $name])->firstOrFail();

		return view('courses.show', ['course' => $course]);
	}

    public function settings(Request $request, $name) {
        $course = Course::where(['name' => $name])->firstOrFail();

        return view('courses.settings', ['course' => $course]);
    }

    public function saveSettings(Request $request) {

    }

    public function new(Request $request) {
        return view('courses.new', []);
    }

    protected function getTree($github, $user, $name, $sha, $tree = [], $path = '')
    {
        $response = $github->git()->trees()->show($user, $name, $sha);

        foreach ($response['tree'] as $obj) {
            if ($obj['type'] == 'blob') {
                $obj['full_path'] = $path . $obj['path'];
                $tree[] = $obj;
            } else if ($obj['type'] == 'tree') {
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

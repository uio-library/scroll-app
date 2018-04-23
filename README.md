
## Setup

- Copy `.env.example` to `.env` and add `DB_*` settings.
- `composer install` to fetch PHP dependencies
- `npm install` to fetch JS dependencies
- `php artisan migrate` to populate database tables
- `php artisan storage:link` to link `public/storage` to `storage/app/public`
- `php artisan key:generate` to add a key to your `.env` file

### Local development

	npm run dev

This will start a local server on http://localhost:3000/ .
It will also start a `php artisan course:watch` process that watches for changes to courses.
When changes are detected, it runs `php artisan course:load` before notifying browsersync to reload the browser.

### Production build

	npm run prod

## Importing a course

Add your course folder under `storage/app/courses`, then run

	php artisan courses:load

This will import/update all courses.

## Course structure

A course consists of

* `{course-name}` : Course contents
* `{course-name}/course.json` : Header, footer, list of modules
* `{course-name}/modules/{module-name}.md` : Course module as Markdown
* `{course-name}/exercises/{exercise-name}.json` : Exercise as JSON
* `{course-name}/resources/{resource-name}.(jpg|png|...)` : Images


## The `course.json` file

TODO

## Exercise types


### Text field question

TODO

### Multiple choice question

TODO

## Other components

### Video

To embed a YouTube video with identifier `AL0ecpu86Gk`:

	<youtube-video id="AL0ecpu86Gk"></youtube-video>

The player will resize to fit the width of the module and default to aspect ratio `16:9`.
For other aspect ratios, you can set the `aspect-ratio` argument to the height divided by the width.
For instance, for a `4:3` video, set `aspect-ratio` to `3 / 4 = 0.75`:

	<youtube-video id="AL0ecpu86Gk" :aspect-ratio="0.75"></youtube-video>

TODO: Markdown syntax?
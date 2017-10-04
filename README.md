
## Local development

	composer install
	npm install
	npm run watch &
	php artisan serve

## Production build

	composer install
	npm install
	npm run prod


## Component documentation


## Text field question

TODO

## Multiple choice question

TODO

## Video

To embed a YouTube video with identifier `AL0ecpu86Gk`:

	<youtube-video id="AL0ecpu86Gk"></youtube-video>

The player will resize to fit the width of the module and default to aspect ratio `16:9`.
For other aspect ratios, you can set the `aspect-ratio` argument to the height divided by the width.
For instance, for a `4:3` video, set `aspect-ratio` to `3 / 4 = 0.75`:

	<youtube-video id="AL0ecpu86Gk" :aspect-ratio="0.75"></youtube-video>

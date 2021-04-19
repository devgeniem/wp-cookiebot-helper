# WordPress Plugin Cookiebot

WordPress plugin for adding placeholder content alongside content blocked by Cookiebot.

## Features

The plugin reads user's cookie consent state from the Cookiebot cookie. Currently the plugin includes a handler for YouTube embeds. The YouTube handler can either show a link to ask user to give their consent to be able to view the video (default functionality) or show the video thumbnail with a link to view the video in YouTube. The placeholder type can be changed in theme with a filter.

The existing handler(s) can be extended pretty easily, and handlers for different content types (f. ex. Vimeo) can be added when needed (the YouTube handler can be used as an example for implementation).

## Filters

### YouTube

#### Change placeholder output

`cookiebot_helper_youtube_placeholder_output`

#### Change placeholder type

`cookiebot_helper_youtube_placeholder_type` - `renew` (default) or `image`

#### Change thumbnail image size

`cookiebot_helper_youtube_image_size` - see the filter description and YouTube API implementation for different sizes

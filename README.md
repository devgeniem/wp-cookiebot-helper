# WordPress Plugin Cookiebot

WordPress plugin for adding placeholder content alongside content blocked by Cookiebot.

## Features

The plugin reads user's cookie consent state from the Cookiebot cookie (see note below). Currently the plugin includes a handler for YouTube embeds. The YouTube handler can either show a link to ask user to give their consent to be able to view the video (default functionality) or show the video thumbnail with a link to view the video in YouTube. The placeholder type can be changed in theme with a filter.

The existing handler(s) can be extended pretty easily, and handlers for different content types (f. ex. Vimeo) can be added when needed (the YouTube handler can be used as an example for implementation).

### Notes about caching

Currently the consent cookie is _not_ parsed server-side as it's not needed (see `CookiebotPlugin::init_handlers()`) by current handlers. This is because if we determine whether the content placeholders should be rendered server-side, there's a high chance the non-placeholder version would be saved to cache (f. ex. Nginx fullpage cache), and the placeholder wouldn't then be shown to anyone. Because of that we _always_ render the placeholder. We also add a class hiding the placeholder initially to avoid content flashing before Cookiebot does its magic. The class is removed with event listener triggering after Cookiebot has processed the tags.

If you add a feature requiring cookie parsing server-side (f. ex. fetching some consent-specific content with Ajax) you can take the `CookieHandler` class into use.

## Filters

### YouTube

#### Change placeholder output

`cookiebot_helper_youtube_placeholder_output`

#### Change placeholder type

`cookiebot_helper_youtube_placeholder_type` - `renew` (default) or `image`

#### Change thumbnail image size

`cookiebot_helper_youtube_image_size` - see the filter description and YouTube API implementation for different sizes

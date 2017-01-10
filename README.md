# ThemeIsle Slack /commands for visual regression testing

## Installation

1. Run `npm install`
2. Send Slack commands `POST` requests to `http://your.server.here/slack-commands/themeisle-wraith-commands.php`

## Prerequsites

1. Install Wraith and Deps 
```
apt-get install imagemagick
npm install casperjs [OR] npm install phantomjs [OR BOTH] 
gem install wraith [! Ruby required]
```

## Commands

1. `/history [theme-slug]` - generates a reference point for the theme slug hosted at `https://demo.themeisle.com/`
2. `/wraith [theme-slug]` - generates a snap of the theme slug hosted at `https://demo.themeisle.com/` and compares it
with the reference point generated by running `/history` command. **Generates a report**.
3. `/wraith_compare [http://first.domain vs http://second.domain]` compares the two provided domains visually. 
**Generates a report**.
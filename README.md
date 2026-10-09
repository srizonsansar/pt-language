# Fala Comigo 🇵🇹

Beginner **European Portuguese** for everyday life in Portugal — vocabulary flashcards, chat-style conversations with role-play, 7 quiz games, grammar basics and a phrasebook. Audio uses the browser's built-in Portuguese voice.

No database: PHP + SCSS + vanilla JS. Progress (XP, streak, learned words) is stored in the visitor's browser (localStorage).

## Run locally
```bash
npm install        # only needed for SCSS
npm run build      # compile assets/scss -> assets/css/style.css
npm run serve      # php -S localhost:8000 router.php  (any port works)
```
Use `npm run watch` while editing styles.

## Structure
```
index.php            Home: word of the day, progress, learning path
learn.php            Topics + flashcards (?topic=food)
conversations.php    Dialogues: watch + role-play (?id=cafe)
quiz.php             7 quiz games (?topic=food&mode=choose)
basics.php           Pronunciation, o/a, ser vs estar, verbs…
phrasebook.php       Searchable survival phrases
data/                ALL CONTENT — edit these PHP arrays to add words, dialogues, sentences
includes/            Shared header/footer + helpers
assets/scss|css|js   Styles and scripts
```

## Adding content
- **New word:** add a line to a topic in `data/vocab.php` (`pt`, `en`, `ne`, `e` emoji, optional `n` note).
- **New topic:** copy a topic block in `data/vocab.php` with a new key.
- **New conversation:** copy a block in `data/dialogues.php`. `you` is the speaker the learner plays in role-play.
- **Sentences / grammar quiz / phrasebook:** `data/phrases.php`.

## Clean URLs
Pages are reached without `.php` (`/learn`, `/quiz?topic=food`). On Apache (MAMP, most hosts) `.htaccess` handles it; with PHP's built-in server use `router.php`. Links are relative, so any port or subfolder works.

## Deploy
Upload everything except `node_modules` and `assets/scss` (optional) to any PHP 7.4+ host. `assets/css/style.css` must be built first.

# 🎬 Movie Vault

Movie Vault is a fun and easy way to track your movie and TV show collection! Add titles to your personal vault (what you own) and keep a wishlist for future additions.

## Features

Explore Movies & TV Shows: Discover new favorites.

Your Vault: Keep track of what you own.

Wishlist: Save movies and shows you want to watch or buy.

## Installation

After cloning this repo, run the following commands from your project root:

```
cp .env.example .env
composer install
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan flux:activate
npm install
npm run dev
```

Make sure your .env file includes:

```
MOVIE_API_TOKEN=your_tmdb_api_key (https://developer.themoviedb.org/docs/getting-started)
DB_CONNECTION=mysql
DB_DATABASE=movie_vault
DB_USERNAME=root
DB_PASSWORD=
```

Now, open the project in your browser (Typically http://movie-vault.test) and use the following credentials to log in:

```
Email: admin@test.com
Password: Password
```

## Testing

Run tests with:

`php artisan test`


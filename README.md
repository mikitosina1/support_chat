# SupportChat

A Laravel module for a support chat between authenticated users and administrators. It provides room and message APIs, a Blade chat component, translations, and message-state events.

## Features

- User API to get the current session/room, create or resolve a room, and list or send messages.
- Admin API to list and view rooms, close rooms, and send or inspect messages.
- Read and delivery state updates for messages.
- Sanctum authentication, admin middleware, room policy, localized UI, and Laravel events for chat activity.
- Host-rendered Blade component with module assets discovered by the host Vite setup.

## API

| Method | Endpoint | Purpose | Access |
| --- | --- | --- | --- |
| `GET` | `/api/v1/support-chat/session` | Current chat session data | `auth:sanctum` |
| `GET` | `/api/v1/support-chat/rooms/current` | Current room | `auth:sanctum` |
| `POST` | `/api/v1/support-chat/rooms` | Create or resolve a room | `auth:sanctum` |
| `PATCH` | `/api/v1/support-chat/rooms/{room}/resolve` | Resolve a room | `auth:sanctum` |
| `GET` | `/api/v1/support-chat/rooms/{room}/messages` | List room messages | `auth:sanctum` |
| `POST` | `/api/v1/support-chat/rooms/{room}/messages` | Send a message | `auth:sanctum` |
| `PATCH` | `/api/v1/support-chat/rooms/{room}/messages/delivered` | Mark messages delivered | `auth:sanctum` |
| `PATCH` | `/api/v1/support-chat/rooms/{room}/messages/read` | Mark messages read | `auth:sanctum` |
| `GET` | `/api/v1/support-chat/translations` | UI translations | `auth:sanctum` |
| `GET` | `/api/v1/admin/support-chat/rooms` | List rooms | `auth:sanctum`, `is_admin` |
| `GET` | `/api/v1/admin/support-chat/rooms/{room}` | Show a room | `auth:sanctum`, `is_admin` |
| `PATCH` | `/api/v1/admin/support-chat/rooms/{room}/close` | Close a room | `auth:sanctum`, `is_admin` |
| `GET` | `/api/v1/admin/support-chat/rooms/{room}/messages` | List room messages | `auth:sanctum`, `is_admin` |
| `POST` | `/api/v1/admin/support-chat/rooms/{room}/messages` | Send a message | `auth:sanctum`, `is_admin` |
| `PATCH` | `/api/v1/admin/support-chat/rooms/{room}/messages/delivered` | Mark messages delivered | `auth:sanctum`, `is_admin` |
| `PATCH` | `/api/v1/admin/support-chat/rooms/{room}/messages/read` | Mark messages read | `auth:sanctum`, `is_admin` |

The route service provider adds the `/api` prefix shown above. The host may add further prefixes; check its route list for final URLs. The host must provide the `is_admin` middleware alias.

## Installation in a host application

Place this repository at `Modules/SupportChat` in a Laravel host configured with `nwidart/laravel-modules`. Install Composer dependencies from the host root and enable the module:

```sh
git clone https://github.com/mikitosina1/support_chat.git Modules/SupportChat
php artisan module:enable SupportChat
php artisan migrate
```

The service provider registers the module routes, views, translations, policy, and migrations. Build the module assets from the host root using its Vite configuration:

```sh
npm install
npm run build
```

The host must also configure its broadcasting driver and credentials if it uses real-time events. Review the host's broadcasting configuration and `.env`; do not assume the old BeyondCode WebSockets setup described in earlier documentation.

## Requirements and compatibility

The current module manifest declares PHP `^8.1`, Laravel `^10.10`, Laravel UI `^4.5`, and `nwidart/laravel-modules` `^10.0`. The module is also maintained inside a newer host application; compatibility with another host version must be checked through that host's dependency resolution. See `composer.json`, `package.json`, and `module.json` for the current metadata.

Feature tests are in `Tests/Feature`. Run them from the host application so its Laravel bootstrap and authentication setup are used.

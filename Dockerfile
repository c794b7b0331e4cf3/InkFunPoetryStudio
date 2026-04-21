FROM oven/bun:alpine AS frontend

COPY . /app

WORKDIR /app

RUN bun install
RUN bun run build

FROM ghcr.io/endless-spike-studio/endless-services-runtime:main

COPY . /app

WORKDIR /app

COPY --from=frontend /app/public/build /app/public/build

RUN composer install --no-dev

RUN php /app/artisan storage:link

ENTRYPOINT ["php", "artisan", "octane:start", "--server", "frankenphp", "--host", "0.0.0.0"]

EXPOSE 8000

FROM ghcr.io/endless-spike-studio/endless-services-runtime:main

ARG APP_URL

COPY --from=oven/bun:alpine /usr/local/bin/bun /usr/local/bin/bun

WORKDIR /app

COPY . .

ENV APP_URL=$APP_URL

RUN composer install --no-dev

RUN bun install
RUN bun run build

RUN php /app/artisan storage:link

ENTRYPOINT ["php", "artisan", "octane:start", "--server", "frankenphp", "--host", "0.0.0.0"]

EXPOSE 8000

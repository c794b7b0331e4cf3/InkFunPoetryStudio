FROM ghcr.io/endless-spike-studio/endless-services-runtime:main

COPY --from=oven/bun:alpine /usr/local/bin/bun /usr/local/bin/bun

WORKDIR /app

COPY . .

RUN composer install --no-dev

RUN bun install
RUN bun run build

RUN php /app/artisan storage:link

ENTRYPOINT ["php", "artisan", "octane:start", "--server", "frankenphp", "--host", "0.0.0.0"]

EXPOSE 8000

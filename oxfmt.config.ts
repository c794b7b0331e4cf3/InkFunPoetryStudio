import { defineConfig } from "oxfmt";

export default defineConfig({
    ignorePatterns: ["public/**", "_generated/**", "*.blade.ts"],
    insertFinalNewline: false,
    tabWidth: 4,
    vueIndentScriptAndStyle: true,
});
import type { App } from 'vue';
import { focus } from './focus';
import { reveal } from './reveal';

export function registerDirectives(app: App): void {
    app.directive('focus', focus);
    app.directive('reveal', reveal);
}

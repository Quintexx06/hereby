import type { App } from 'vue';
import { countUp } from './countUp';
import { focus } from './focus';
import { reveal } from './reveal';

export function registerDirectives(app: App): void {
    app.directive('count-up', countUp);
    app.directive('focus', focus);
    app.directive('reveal', reveal);
}

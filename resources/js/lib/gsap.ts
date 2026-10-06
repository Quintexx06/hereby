import { gsap } from 'gsap';
import { CustomEase } from 'gsap/CustomEase';
import { DrawSVGPlugin } from 'gsap/DrawSVGPlugin';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { easing } from '@/lib/motion';

/**
 * Single place where GSAP plugins and brand eases are registered.
 * Import gsap from here, never from 'gsap' directly.
 */
gsap.registerPlugin(CustomEase, DrawSVGPlugin, ScrollTrigger, SplitText);

CustomEase.create('hereby.out', easing.out);
CustomEase.create('hereby.inOut', easing.inOut);
CustomEase.create('hereby.drawer', easing.drawer);

gsap.defaults({ ease: 'hereby.out', duration: 0.32 });

export { gsap, ScrollTrigger, SplitText };

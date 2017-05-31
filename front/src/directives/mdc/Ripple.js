import { MDCRipple } from '@material/ripple';

export default {
  bind(el, context) {
    const element = el;

    if (!context.modifiers.custom) {
      element.classList.add('mdc-ripple-surface');
    }
    element.mdc_ripple = MDCRipple.attachTo(element, { isUnbounded: context.modifiers.unbounded });
  },
  unbind(el) {
    const element = el;

    if (!element.mdc_ripple) {
      return;
    }

    element.mdc_ripple.destroy();
    delete element.mdc_ripple;
  },
};

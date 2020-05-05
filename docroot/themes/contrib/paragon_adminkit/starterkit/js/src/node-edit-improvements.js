/**
 * @file
 * Collapse meta data into off screen.
 */

Drupal.behaviors.nodeEditImprovements = {
  attach: (context) => {
    const nodeForm = context.querySelector('.layout-node-form');
    const body = context.querySelector('body');
    if (!nodeForm || !body) return;

    const meta = nodeForm.querySelector('.layout-region-node-secondary');
    const button = nodeForm.querySelector('.toggle-meta');
    const openClass = 'meta-open';

    // If this is an add page, open the meta drawer.
    if (!body.classList.contains('edit-node')) {
      meta.classList.add(openClass);
    }

    // On button click, toggle the meta open / close.
    button.addEventListener('click', (event) => {
      event.preventDefault();
      if (meta.classList.contains(openClass)) {
        meta.classList.add('closing');
        setTimeout(() => {
          meta.classList.remove('closing');
        }, 1000); // Time here needs to match CSS timing.
      }
      meta.classList.toggle(openClass);
    });
  },
};

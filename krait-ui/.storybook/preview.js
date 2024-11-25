/** @type { import('@storybook/vue3').Preview } */

// This should be implemented in the Main Laravel application,
//      adding it here just for the preview purposes
import '../node_modules/bootstrap/dist/css/bootstrap.css';
import '../node_modules/@fortawesome/fontawesome-free/css/all.css';
import Mocker from './mocker';

const preview = {
  parameters: {
    controls: {
      matchers: {
        color: /(background|color)$/i,
        date: /Date$/,
      },
    },
  },
};

Mocker.init();

export default preview;

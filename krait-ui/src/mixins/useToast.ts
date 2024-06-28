import { Ref, ref } from 'vue';

interface IMessage {
  content: string;
  type: MessageTypes;
  timing: number;
}
interface IShowMessagesOptions {
  type: IMessage['type'];
  timing: IMessage['timing'];
}

export enum MessageTypes {
  Primary = 'primary',
  Danger = 'danger',
  Info = 'info',
  Success = 'success',
}

export const messages: Ref<IMessage[]> = ref([]);
const defaultOptions: IShowMessagesOptions = {
  type: MessageTypes.Primary,
  timing: 5000,
};

/**
 * Shows a new alert message.
 *
 * @param {string} content - The alert message.
 * @param {Partial<IShowMessagesOptions>} options - The additional properties.
 * @param {MessageTypes} [options.type = 'primary'] - The alert type.
 * @param {number} [options.timing = 5000] - The alert show timing.
 */
const showMessage = (
  content: string,
  options: Partial<IShowMessagesOptions> = defaultOptions,
) => {
  const message: IMessage = {
    content,
    ...defaultOptions,
    ...options,
  };
  const index = messages.value.push(message);

  setTimeout(() => {
    messages.value.splice(index - 1, 1);
  }, message.timing);
};

export default () => ({
  showMessage,
  messages,
});

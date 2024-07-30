import { readonly, ref } from 'vue';

const isOpened = ref<boolean>(false);
const question = ref<string>();

let confirmResolve: any = () => {};
const agree = () => {
  confirmResolve(true);
  isOpened.value = false;
};
const cancel = () => {
  confirmResolve(false);
  isOpened.value = false;
};

const ask = async (content: string): Promise<boolean> => {
  question.value = content;
  isOpened.value = true;

  return new Promise<boolean>((resolve) => {
    confirmResolve = resolve;
  });
};

export default () => ({
  ask,
  isOpened: readonly(isOpened),
  agree,
  cancel,
  question,
});

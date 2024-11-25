export const isNumber = (value) => {
  return typeof value === 'number' && !isNaN(value);
};

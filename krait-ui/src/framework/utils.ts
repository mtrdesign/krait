/**
 * Sanitises a path string to ensure that
 * it doesn't end with a slash.
 *
 * @param {string} path - The raw path.
 */
export const sanityPath = (path: string) => {
  if (path.endsWith('/')) {
    path = path.slice(0, -1);
  }

  return path;
};

/**
 * Creates a debounced function that delays the invocation
 * of the provided function until after the specified timeout
 * has elapsed since the last time the debounced function was called.
 *
 * @param {Function} callback - The function to debounce.
 * @param {number} [wait=300] - The number of milliseconds to delay.
 * @param {boolean} [immediate=false] - The number of milliseconds to delay.
 * @returns {Function} A new debounced function.
 */
export function debounce<T extends (...args: any[]) => void>(
  callback: T,
  wait: number,
  immediate = false,
) {
  // This is a number in the browser and an object in Node.js,
  // so we'll use the ReturnType utility to cover both cases.
  let timeout: ReturnType<typeof setTimeout> | null;

  return function <U>(this: U, ...args: Parameters<typeof callback>) {
    const context = this;
    const later = () => {
      timeout = null;

      if (!immediate) {
        callback.apply(context, args);
      }
    };
    const callNow = immediate && !timeout;

    if (typeof timeout === 'number') {
      clearTimeout(timeout);
    }

    timeout = setTimeout(later, wait);

    if (callNow) {
      callback.apply(context, args);
    }
  };
}

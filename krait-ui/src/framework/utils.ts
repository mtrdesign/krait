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

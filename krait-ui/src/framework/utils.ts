export const sanityPath = (path: string) => {
  if (path.endsWith('/')) {
    path = path.slice(0, -1);
  }

  return path;
};

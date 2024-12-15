module.exports = async ({ github, context, inputs }) => {
  const majorVersion = inputs.tagName.split(".")[0];

  // Cleanup beta releases
  console.log("Cleaning up beta releases...");
  const { data: releases } = await github.rest.repos.listReleases({
    owner: context.repo.owner,
    repo: context.repo.repo,
  });

  for (const release of releases) {
    if (
      !release.tag_name.startsWith(`${majorVersion}.`) ||
      !release.tag_name.includes("-beta")
    ) {
      continue;
    }

    console.log(`Deleting beta release: ${release.tag_name}`);
    await github.rest.repos.deleteRelease({
      owner: context.repo.owner,
      repo: context.repo.repo,
      release_id: release.id,
    });
  }

  // Cleanup beta tags
  console.log("Cleaning up beta tags...");
  const { data: tags } = await github.rest.repos.listTags({
    owner: context.repo.owner,
    repo: context.repo.repo,
  });
  for (const tag of tags) {
    if (
      !tag.name.startsWith(`${majorVersion}.`) ||
      !tag.name.includes("-beta")
    ) {
      continue;
    }

    console.log(`Deleting beta tag: ${tag.name}`);
    await github.rest.git.deleteRef({
      owner: context.repo.owner,
      repo: context.repo.repo,
      ref: `refs/tags/${tag.name}`,
    });
  }
};

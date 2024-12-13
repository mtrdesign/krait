module.exports = async ({ github, context, inputs }) => {
  const majorVersion = inputs.tagName.split(".")[0];

  // Get current release
  const { data: release } = await github.rest.repos.getRelease({
    owner: context.repo.owner,
    repo: context.repo.repo,
    release_id: inputs.releaseId,
  });

  console.log("Updating release tag...");
  await github.rest.repos.updateRelease({
    owner: context.repo.owner,
    repo: context.repo.repo,
    release_id: release.id,
    name: inputs.tagName,
    tag_name: inputs.tagName,
    draft: false,
    prerelease: false,
  });

  // Cleanup beta releases
  console.log("Cleaning up beta releases...");
  const { data: releases } = await github.rest.repos.listReleases({
    owner: context.repo.owner,
    repo: context.repo.repo,
  });

  for (const release of releases) {
    if (
      release.tag_name.startsWith(`${majorVersion}.`) &&
      release.tag_name.includes("-beta")
    ) {
      console.log(`Deleting beta release: ${release.tag_name}`);
      await github.rest.repos.deleteRelease({
        owner: context.repo.owner,
        repo: context.repo.repo,
        release_id: release.id,
      });
    }
  }

  // Cleanup beta tags
  console.log("Cleaning up beta tags...");
  const { data: tags } = await github.rest.repos.listTags({
    owner: context.repo.owner,
    repo: context.repo.repo,
  });
  for (const tag of tags) {
    if (tag.name.startsWith(`${majorVersion}.`) && tag.name.includes("-beta")) {
      console.log(`Deleting beta tag: ${tag.name}`);
      await github.rest.git.deleteRef({
        owner: context.repo.owner,
        repo: context.repo.repo,
        ref: `refs/tags/${tag.name}`,
      });
    }
  }
};

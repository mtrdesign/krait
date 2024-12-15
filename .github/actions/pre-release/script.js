module.exports = async ({ github, context, inputs }) => {
  const draftTagName = `${inputs.branchName}-unreleased`;

  const releases = await github.rest.repos.listReleases({
    owner: context.repo.owner,
    repo: context.repo.repo,
  });

  const release = releases.data.find(
    (release) => release.tag_name == draftTagName && release.draft,
  );
  if (!release) {
    throw new Error("Draft release not found!");
  }

  console.log("Updating the draft release...");
  github.rest.repos.updateRelease({
    owner: context.repo.owner,
    repo: context.repo.repo,
    release_id: release.id,
    name: inputs.tagName,
    tag_name: inputs.tagName,
    draft: false,
    prerelease: true,
  });

  return release.id;
};

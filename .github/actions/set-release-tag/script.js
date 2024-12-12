module.exports = async ({ github, context, inputs }) => {
  const releases = await github.rest.repos.listReleases({
    owner: context.repo.owner,
    repo: context.repo.repo,
    tag_name: "unreleased",
  });
  let release = releases.data.find(
    (release) =>
      release.draft == true &&
      release.tag_name === `unreleased[${inputs.branchName}]`,
  );

  if (release) {
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
  } else {
    console.log("Creating a new pre-release...");
    release = github.rest.repos.createRelease({
      owner: context.repo.owner,
      repo: context.repo.repo,
      name: inputs.tagName,
      tag_name: inputs.tagName,
      draft: false,
      prerelease: true,
      generate_release_notes: true,
    });
  }

  return release.id;
};

module.exports = async ({ github, context, inputs }) => {
  // const majorVersion = inputs.branchName.split(".")[0];
  const draftTagName = `unreleased[${inputs.branchName}]`;

  const releases = await github.rest.repos.listReleases({
    owner: context.repo.owner,
    repo: context.repo.repo,
  });

  // const preReleases = releases.data.filter((release) => release.prerelease);

  // // Ensure that the other pre-releases are merged to the current one
  // for (const preRelease of preReleases) {
  //   if (preRelease.tag_name.startsWith(`${majorVersion}.`)) {
  //     // Delete the release
  //     await github.rest.repos.deleteRelease({
  //       owner: context.repo.owner,
  //       repo: context.repo.repo,
  //       release_id: preRelease.id,
  //     });

  //     // Delete the tag
  //     await github.rest.git.deleteRef({
  //       owner: context.repo.owner,
  //       repo: context.repo.repo,
  //       ref: `tags/${preRelease.tag_name}`,
  //     });
  //   }
  // }

  const release = releases.data.find(
    (release) => release.tag_name == draftTagName && release.draft,
  );
  if (release) {
    console.log("Updating the draft release...");
    const notes = await github.rest.repos.generateReleaseNotes({
      owner: context.repo.owner,
      repo: context.repo.repo,
      tag_name: inputs.tagName,
    });
    github.rest.repos.updateRelease({
      owner: context.repo.owner,
      repo: context.repo.repo,
      release_id: release.id,
      name: inputs.tagName,
      body: notes.data.body,
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

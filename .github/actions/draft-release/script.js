module.exports = async ({ github, context, inputs }) => {
  const tagName = `unreleased[${inputs.branchName}]`;
  const releases = await github.rest.repos.listReleases({
    owner: context.repo.owner,
    repo: context.repo.repo,
    tag_name: tagName,
  });

  const filtered = releases.data.filter(
    (release) => release.draft == true && release.tag_name === tagName,
  );

  if (filtered.length > 0) {
    console.log("Deleting old untagged release");
    await github.rest.repos.deleteRelease({
      owner: context.repo.owner,
      repo: context.repo.repo,
      release_id: filtered[0].id,
    });

    try {
      console.log(`Deleting old '${tagName}' tag`);
      await github.rest.git.deleteRef({
        owner: context.repo.owner,
        repo: context.repo.repo,
        ref: `tags/${tagName}`,
      });
      console.log(`The '${tagName}' tag has been deleted`);
    } catch (error) {
      console.log(`Tag '${tagName}' does not exist or could not be deleted`);
    }
  }

  console.log(`Creating a new untagged release for ${inputs.branchName}`);
  await github.rest.repos.createRelease({
    owner: context.repo.owner,
    repo: context.repo.repo,
    tag_name: tagName,
    name: `Unreleased [${inputs.branchName}]`,
    draft: true,
    generate_release_notes: true,
  });
};

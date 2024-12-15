module.exports = async ({ github, context, inputs }) => {
  const tagName = `${inputs.branchName}-unreleased`;
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

  console.log("Getting the latest tag");
  const majorVersion = inputs.branchName.split(".")[0];
  const { data: tags } = await github.rest.repos.listTags({
    owner: context.repo.owner,
    repo: context.repo.repo,
  });
  const latestTag = tags.find(
    (tag) =>
      tag.name.startsWith(`${majorVersion}.`) && !tag.name.includes("-beta"),
  );
  if (latestTag) {
    console.log(`Found latest tag: ${latestTag.name}`);
  } else {
    console.log(`No matching tags found for major version ${majorVersion}`);
  }

  console.log(`Creating a new untagged release for ${inputs.branchName}`);
  const { data: notes } = await github.rest.repos.generateReleaseNotes({
    owner: context.repo.owner,
    repo: context.repo.repo,
    tag_name: tagName,
    target_commitish: inputs.branchName,
    previous_tag_name: latestTag?.name,
  });
  await github.rest.repos.createRelease({
    owner: context.repo.owner,
    repo: context.repo.repo,
    tag_name: tagName,
    name: `Unreleased [${inputs.branchName}]`,
    body: notes.body,
    draft: true,
    target_commitish: inputs.branchName,
  });
};

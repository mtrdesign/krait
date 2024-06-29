const assets = JSON.parse(process.argv[2]);
const buildAsset = assets.find(asset => asset.name === 'distribution-package.zip')
console.log(buildAsset.browser_download_url)

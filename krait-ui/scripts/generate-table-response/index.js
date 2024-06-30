#!/usr/bin/env node

import { parse } from 'csv-parse';
import * as fs from "fs";
import { finished } from 'stream/promises';

const path = process.argv[2];
if (!fs.existsSync(path)) {
  throw new Error('Path to the CSV is missing or invalid.');
}

const getColumnPayload = (title) => {
  const name = title.toLowerCase()
    .replaceAll(' ', '_')
    .replaceAll(',', '')
    .replaceAll('.', '');

  return {
    label: title,
    name,
    sortable: true,
    width: 250,
  };
};

const processFile = async () => {
  const columns = [];
  const records = [];

  const parser = fs
    .createReadStream(path)
    .pipe(parse({}));

  parser.on('readable', function(){
    let row;
    while ((row = parser.read()) !== null) {

      if (columns.length === 0) {
        for (const column of row) {
          columns.push(getColumnPayload(column))
        }
        continue;
      }

      const record = {};
      for (let [index, column] of columns.entries()) {
        record[column.name] = row[index] ?? null;
      }
      records.push(record);
    }
  });

  await finished(parser);

  return {
    columns,
    records,
  };
};

const {columns, records} = await processFile();

await fs.promises.writeFile(
  '.storybook/mocks/data.json',
  JSON.stringify({columns, data: records}, null, "\t")
);

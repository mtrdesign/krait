import { ApiClient } from '~/framework';
import BaseAction from './base-action';

interface IFetchRecordsOptions {
  url: string;
  body: BodyInit | null;
}

interface IFetchRecordsResult {
  success: boolean;
}

/**
 * DeleteRecord Action
 * Deletes table record.
 *
 * @class
 * @extends BaseAction
 */
export default class DeleteRecord extends BaseAction<
  IFetchRecordsOptions,
  IFetchRecordsResult
> {
  async process({
    url,
    body = null,
  }: IFetchRecordsOptions): Promise<IFetchRecordsResult> {
    this.context.isLoading.value = true;
    await ApiClient.fetch(url, body, 'DELETE');

    this.context.isLoading.value = false;

    return {
      success: true,
    };
  }
}

import PaginatedListState from 'flarum/common/states/PaginatedListState';
import PaginatedListRequestParams from 'flarum/common/states/PaginatedListState';

export default class CardShowListState extends PaginatedListState {
  constructor(params, page = 1) {
    super(params, page, 12);
    this.cardShowList = [];
  }

  get type() {
    return 'card_show_list';
  }

}

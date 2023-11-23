import Model from 'flarum/Model';
import mixin from 'flarum/utils/mixin';

export default class GetList extends mixin(Model, {
    id: Model.attribute('id'),
    img_list: Model.attribute('img_list'),
    created_time: Model.attribute('created_time'),
    user: Model.hasOne('user'),
    uid: Model.attribute('uid'),
    width: Model.attribute('cover_width'),
    height: Model.attribute('cover_height'),
    like_count: Model.attribute('like_count'),
    view_count: Model.attribute('view_count'),
    exchange_count: Model.attribute('exchange_count'),
    is_my_like: Model.attribute('is_my_like'),
    status: Model.attribute('status'),
  }) {
    apiEndpoint() {
      return '/card_show_list' + (this.exists ? '/' + this.data.id : '');
    }
}
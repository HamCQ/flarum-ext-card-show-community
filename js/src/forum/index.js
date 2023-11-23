import app from 'flarum/forum/app';
import addSidebarNav from './addSiderBar';
import IndexShowPage from './components/IndexShowPage';
import { extend } from 'flarum/common/extend';
import GlobalSearchState from 'flarum/forum/states/GlobalSearchState';
import FileListState from './states/FileListState';
import CardShowListState from './states/CardShowListState';
import GetList from '../common/models/GetList';
import LikeNotification from './notification/LikeNotification';
import NotificationGrid from 'flarum/forum/components/NotificationGrid';

app.initializers.add('hamcq/qsl-card-show', () => {
  app.routes.qslCardShow = {
    path: '/qslCardShow',
    component: IndexShowPage,
  };
  app.notificationComponents.cardShowLiked = LikeNotification;

  app.store.models.cardShowList = GetList;

  app.cardShowListState = new CardShowListState();
  app.fileListState = new FileListState();

  addSidebarNav();
  extend(GlobalSearchState.prototype, 'params', function (params) {
    if(app.current.get('routeName') === 'qslCardShow'){
      params.cardFilter = "";
    }
  });

  extend(NotificationGrid.prototype, 'notificationTypes', function (items) {
    items.add('cardShowLiked', {
      name: 'cardShowLiked',
      icon: 'fas fa-camera-retro',
      label: "有人对您分享的卡片表示很赞",
    });
  });

});

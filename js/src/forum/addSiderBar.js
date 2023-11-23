import { extend } from 'flarum/extend';
import IndexPage from 'flarum/components/IndexPage';
import LinkButton from 'flarum/components/LinkButton';
import Dropdown from 'flarum/common/components/Dropdown';
import Button from 'flarum/common/components/Button';

export default function addSidebarNav() {
  extend(IndexPage.prototype, 'navItems', function (items) {
    items.add(
      'qslCardShow',
      <LinkButton icon="fas fa-camera-retro" href={
        app.route('qslCardShow')
      }>
        {app.translator.trans(`hamcq-qsl-card-show.forum.title.page_title`)}
      </LinkButton>,
      15
    );

    return items;
  });
}

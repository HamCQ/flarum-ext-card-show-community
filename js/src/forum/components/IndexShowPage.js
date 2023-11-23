import Page from 'flarum/components/Page';
import IndexPage from 'flarum/components/IndexPage';
import listItems from 'flarum/common/helpers/listItems';
import FilterMenuItem from './FilterMenuItem';
import Button from 'flarum/common/components/Button';
import UploadModal from './UploadModal';
import LoadingIndicator from 'flarum/components/LoadingIndicator';
import avatar from 'flarum/helpers/avatar';
import Link from 'flarum/components/Link';
import LoginModal from 'flarum/components/LogInModal';
import HideModal from './HideModal';
import { Fancybox } from '@fancyapps/ui';


export default class IndexShowPage extends Page {
    oninit(vnode) {
        super.oninit(vnode);
        this.bodyClass = 'App--index';
        app.setTitle(app.translator.trans(
            `hamcq-qsl-card-show.forum.title.page_title`
        ));

        app.cardShowListState.refreshParams({
            filter: {
                
            },
            sort: '-created_time',
        });
    }

    view(){
        let loading = null;        
        const state = app.cardShowListState;
        if (state.isInitialLoading() || state.isLoadingNext()) {
            loading = LoadingIndicator.component({
              size: 'large',
            });
        } else if (state.hasNext()) {
            loading = Button.component(
              {
                className: 'Button',
                icon: 'fas fa-chevron-down',
                onclick: state.loadNext.bind(state),
              },
              "加载更多"
            );
        }
        if (state.isInitialLoading() && state.isEmpty()) {
            return <LoadingIndicator />;
        }
        Fancybox.bind("[data-fancybox]", {
                on: {
                    "done": (fancybox, slide) => {
                        if(slide.index==0){
                            var gallery = fancybox.getSlide().$trigger.dataset.fancybox;
                            var arr = gallery.split('-');
                            if(arr.length == 2 && arr[1]){
                                var show_id = arr[1]
                                app
                                    .request({
                                        method: 'POST',
                                        url: `${app.forum.attribute('apiUrl')}/hamcq/qsl_card_show/view`,
                                        body: { show_id },
                                        option: {
                                            background: true
                                        }
                                    })
                            }
                        }

                    },
                },
        });
        return(
            <div className="IndexPage">
                {IndexPage.prototype.hero()}
                <div className="container">
                    <div className="sideNavContainer">
                        <nav className="IndexPage-nav sideNav">
                            <ul>{listItems(IndexPage.prototype.sidebarItems().toArray())}</ul>
                        </nav>
                        <div className="IndexPage-results sideNavOffset">
                            <div>
                                <FilterMenuItem
                                    state={state}
                                />
                                <Button
                                    className={`Button cardShow-fresh`}
                                    icon="fas fa-sync" 
                                    aria-label="刷新"
                                    onclick={()=>{
                                        state.refresh()
                                    }}>
                                </Button>
                                <Button
                                    className={`Button cardShow-upload-botton`}
                                    icon="fas fa-plus" 
                                    onclick={()=>{
                                        if(!app.session.user){
                                            app.modal.show(LoginModal)
                                            return;
                                        }
                                        app.modal.show(UploadModal, {
                                            state: state
                                        })
                                    }}>
                                    {app.translator.trans(`hamcq-qsl-card-show.forum.button.share_my_card`)}
                                </Button>
                            </div>
                            <div className="CardShow">
                                {
                                    state.getPages().map((pg)=>{
                                        return pg.items.map((item) => {
                                            return  item.status() && (
                                                //key={item.id()} 
                                                <div id={"card-"+item.id()} className={`card ${this.getClass(item.width(), item.height())}`}>
                                                    <div className="content">
                                                        {
                                                            item.img_list().map((v,k)=>{
                                                                return (
                                                                    <a 
                                                                        style={k==1?"display:none":""}
                                                                        data-fancybox={"gallery-"+item.id()}
                                                                        data-src={v}
                                                                        data-caption={"#"+(k+1)+" via "+item.user().username()}
                                                                    >
                                                                        <img style="width:100%" src={v}></img>
                                                                    </a>
                                                                )
                                                            })
                                                        }
                                                        
                                                    </div>
                                                    <div className="footer">
                                                        <div style="float:left">
                                                            <Link
                                                                href={app.route('user', {
                                                                    username: item.uid(),
                                                                })}
                                                                style="text-decoration: none;"
                                                            >
                                                                {avatar(item.user(),{
                                                                    className: "avatar"
                                                                })}
                                                                <span className="username">{item.user().username()}</span>
                                                            </Link>
                                                        </div>
                                                        <div className="action">
                                                            {this.viewer(item)}
                                                            {this.likeButton(item,state)}
                                                            {this.exchangeButton(item,this)}
                                                        </div>
                                                    </div>
                                                </div>
                                            )
                                        })
                                    })
                                }
                            </div>
                            {<div className="SupportSearchList-loadMore cardShow-more">{loading}</div>}

                        </div>
                    </div>
                </div>
            </div>
        )
    }
    
    getClass(width, height){
        // (/Mobi|Android|iPhone/i.test(navigator.userAgent))
        if(width>height){
            return "tall";
        }
        if (height<1000){
            return "tall";
        }
        if (height>1000){
            return "taller";;
        }
        return "taller";;
    }

    likeStatus(count){
        if (count>0 && count<1000){
            return count
        }
        if(count>=1000){
            return count/1000 + "k"
        }
        return ""
    }

    viewer(item){
        var count = item.view_count();
        if(count>=1000){
            count = count/1000 + "k";
        }
        return (
            <Button
                className={`Button viewer`}
                icon={"far fa-eye" }
                aria-label="浏览量"//防止console报错
                disable={true}
                >
                {count}
            </Button>
        )
    }

    likeButton(item,state){
        return (
            <Button
                className={`Button like`}
                icon={item.is_my_like()?"fas fa-thumbs-up" :"far fa-thumbs-up" }
                aria-label="点赞"//防止console报错
                onclick={() => {
                    if(!app.session.user){
                        app.modal.show(LoginModal)
                        return;
                    }
                    this.like(item.id(),state)
                }}>
                {this.likeStatus(item.like_count())}
            </Button>
        )
    }

    like(show_id,state){
        app
            .request({
                method: 'POST',
                url: `${app.forum.attribute('apiUrl')}/hamcq/qsl_card_show/like`,
                body: { show_id },
            })
            .then((msg) => {
                if(msg.status){
                    $("#card-"+show_id+" .action .like i").removeClass("far fa-thumbs-up")
                    $("#card-"+show_id+" .action .like i").addClass("fas fa-thumbs-up")
                    var origin = $("#card-"+show_id+" .action .like span").text();
                    if(!origin){
                        origin=0;
                    }
                    var likeNum = parseInt(origin)+1;
                    if(origin>=1000){
                        likeNum = origin/1000 + "k";
                    }
                    $("#card-"+show_id+" .action .like span").text(likeNum)
                    return;
                }
            })
    }

    exchangeButton(item,e){
        if(app.session.user && app.session.user.data.id==item.user().id()){
            return(
                <Button
                    className={`Button bulk`}
                    icon="fas fa-trash" 
                    aria-label="删除卡片"
                    onclick={() => {
                        if(!app.session.user){
                            app.modal.show(LoginModal)
                            return;
                        }
                        app.modal.show(HideModal,{
                            show_id: item.id(),
                        })                       
                    }}>
                </Button>
            )
        }
    }
}
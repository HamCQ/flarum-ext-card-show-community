import Component from 'flarum/common/Component';
import Button from 'flarum/common/components/Button';
import Modal from 'flarum/common/components/Modal';
import UserFileList from './UserFileList';
import UploadButton from './UploadButton';
import Uploader from '../handler/Uploader';
import Alert from 'flarum/common/components/Alert';

export default class UploadModal extends Modal {
    oninit(vnode) {
        super.oninit(vnode);
        
        this.uploader = new Uploader();
        this.selectedFiles = [];
        this.multiSelect = vnode.attrs.multiSelect || true;
        this.restrictFileType = vnode.attrs.restrictFileType || null;
        this.state = this.attrs.state.modal.attrs.state;
    }

    title() {
        return app.translator.trans(`hamcq-qsl-card-show.forum.title.share_my_card`);
    }
    
    className() {
        return 'Modal--large fof-file-manager-modal';
    }

    oncreate(vnode) {
        super.oncreate(vnode);
    }

    onremove() {
        
    }

    content(){
        if(!app.session.user){
            return;
        }
        return(
            <div>
                <div className="Modal-body">
                    <p style="margin-bottom: 50px;font-size:15px">{app.translator.trans(`hamcq-qsl-card-show.forum.tips.upload_tips`)}</p>
                    <h3>上传您的图片：</h3>
                        <UploadButton 
                            uploader={this.uploader} 
                            disabled={app.fileListState.isLoading()} 
                            isMediaUploadButton={true}
                        />
                    <h3 style="margin-top: 50px;">请从您的图片库中，选择图片：</h3>
                    <UserFileList
                        user={this.attrs.user}
                        selectable
                        onFileSelect={this.onFileSelect.bind(this)}
                        selectedFiles={this.selectedFiles}
                        restrictFileType={this.restrictFileType}
                    />
                </div>
                <div className="Modal-footer">
                    <Button 
                        onclick={this.onSelect.bind(this)} 
                        disabled={this.selectedFiles.length === 0 || (!this.multiSelect && this.selectedFiles.length > 1)}
                        className="Button Button--primary"
                    >
                        确认
                    </Button>
                    <Button onclick={this.hide.bind(this)} className="Button">
                        取消
                    </Button>
                </div>
            </div>
        )
    }

     /**
     * Add or remove file from selected files
     *
     * @param {File} file
     */
    onFileSelect(file) {
        const itemPosition = this.selectedFiles.indexOf(file.id());
    
        if (itemPosition >= 0) {
          this.selectedFiles.splice(itemPosition, 1);
        } else {
          if (this.multiSelect) {
            if (this.selectedFiles.length==2){
                app.alerts.show({ type: 'info' }, app.translator.trans(`hamcq-qsl-card-show.forum.alerts.less_select`));
                return;
            }
            this.selectedFiles.push(file.id());
          } else {
            this.selectedFiles = [file.id()];
          }
        }
    }
    //submit
    onSelect() {
        if(!this.checkSelectNum()){
            return false;
        }
        var uuidList = [];
        var width = 0;
        var height = 0;
        this.selectedFiles.map((fileId) => {
            const file = app.store.getById('files', fileId);
            uuidList.push(file.id())
        });
        if(this.selectedFiles.length>=1){
            const file = app.store.getById('files', this.selectedFiles[0]);
            // 创建对象
            var img = new Image()
            img.src = file.url()
            if(img.complete){
                width = img.width;
                height = img.height;
            }else{
                img.onload = function(){
                    width = img.width;
                    height = img.height;
                }
            }
        }
        app
            .request({
                method: 'POST',
                url: `${app.forum.attribute('apiUrl')}/hamcq/qsl_card_show/add`,
                body: { uuidList,width,height },
            })
            .then(() => {
                app.alerts.show(Alert, { type: 'success' }, "分享成功");
                this.hide();
                this.state.refresh();
            })
            
    }

    checkSelectNum(){
        if (this.selectedFiles.length>2){
            app.alerts.show({ type: 'info' }, app.translator.trans(`hamcq-qsl-card-show.forum.alerts.less_select`));
            return false;
        }
        return true;
    }
}
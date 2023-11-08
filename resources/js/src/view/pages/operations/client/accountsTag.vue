<template>
  <div class="col-lg-12 col-mg-12 col-sm-12 mb-3 ">
    <label><strong>{{ labelTag }}</strong></label>
    <input
      :name="idTag"
      class="form-control tagify_convert"
      :ref="idTag"
      value=""
      disabled
      @change="TagifyInput(idTag)"
    />
  </div>
</template>
<style lang="scss">
  @import "@/assets/libs/tagify/tagify.scss";
</style>
<script>
    // import Tagify from '@yaireo/tagify';
    export default {
      name: "accountsTag",
      props: ['accounts','labelTag','idTag'],
      data() {
        return {
          TagifyUserList:''
        }
      },
      mounted() {
          this.bankCreateSelect();// sirve para el breadcums console.log(this.$route.path.split('/'))
      },
      methods: {
        TagifyInput(id) {
          setTimeout(() => {

            let form = this.idTag == 'bankRecived' 
            ? 'selectReceiveBank' 
            : this.idTag == 'bankSend' 
            ? 'selectSendBank'
            :'selectTransferBank';
            
            let valueInArray ={}
            if(this.$refs[this.idTag].value !==''){
              
                try {
                  valueInArray.data = JSON.parse(this.$refs[this.idTag].value)
                } catch (error) {
                }
                
                
            }
            valueInArray.typeOperationBank = id;
            this.$emit(form,valueInArray);
          }, 200);
          
        },
        tagTemplate(tagData) {
          this.$refs[this.idTag].value = tagData.value;
          document.querySelector('[name="'+this.idTag+'"]').value = this.$refs[this.idTag].value;
          
          return `
            <tag title="${tagData.bank.name}"
              contenteditable='false'
              spellcheck='false'
              tabIndex="-1"
              class="tagify__tag"
              value="${tagData.value}"
              name="${tagData.bank.name}"
              logo="${tagData.bank.logo}"
              alias="${ tagData.alias }"
              
            >
              <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
              <div>
                <div class='tagify__tag__avatar-wrap'>
                  <img onerror="this.style.visibility='hidden'" src="${tagData.bank.logo}">
                </div>
                <span class='tagify__tag-text'>${tagData.account_number} | ${ tagData.alias }</span>
              </div>
            </tag>
          `;
        },
  
        suggestionItemTemplate(tagData) {
          if (this.$refs[this.idTag].value.length < 1) {
            return `
                <div 
                  value="${tagData.value}" name="${tagData.bank.name}" namex="${tagData.nameBank}" logo="${tagData.bank.logo}" alias="${ tagData.alias }" mappedvalue="${tagData.mappedValue}"
                  class='tagify__dropdown__item align-items-center ${tagData.class ? tagData.class : ''}'
                  tabindex="0"
                  role="option"
                >
                  ${tagData.bank.logo
                ? `<div class='tagify__dropdown__item__avatar-wrap'>
                      <img onerror="this.style.visibility='hidden'" src="${tagData.bank.logo}">
                    </div>`
                : ''
              }
                  <strong>${tagData.bank.name} | ${tagData.account_number} | ${ tagData.alias }</strong>
                </div>`;
          }
        },
        bankCreateSelect() {
          const TagifyUserListEl = this.$refs[this.idTag];
          setTimeout(() => {
            this.$refs[this.idTag].removeAttribute('disabled')
  
            this.TagifyUserList = new Tagify(TagifyUserListEl, {
              texts: {
                notAllowed: "not allowed"
              },
              tagTextProp: 'name', // very important since a custom template is used with this property as text. allows typing a "value" or a "name" to match input with whitelist
              enforceWhitelist: true,
              maxTags: 1,
              editTags: false,
              skipInvalid: false, // do not remporarily add invalid tags
              dropdown: {
                closeOnSelect: true,
                enabled: 0,
                classname: 'users-list',
                searchKeys: ['nameBank','alias'] // very important to set by which keys to search for suggesttions when typing
              },
              templates: {
                tag: this.tagTemplate,
                dropdownItem: this.suggestionItemTemplate
              },
              whitelist: this.accounts
            });
            
          }, 500);
        },
      }
    };
</script>
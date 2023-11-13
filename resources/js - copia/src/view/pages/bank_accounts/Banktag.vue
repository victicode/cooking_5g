<template>
  <div class="col-12">
    <label for="TagifyUserList" class="form-label">Banco</label>
    <input
      id="TagifyUserList"
      name="TagifyUserList"
      class="form-control"
      :ref="'bankSel'"
      value=""
      @change="TagifyInput()"
    />
  </div>
</template>
<style lang="scss">
	@import "@/assets/libs/tagify/tagify.scss";
</style>
<script>
  // import Tagify from '@yaireo/tagify';
  export default {
    name: "BankTag",
    props: ['banks'],
    data() {
      return {
        TagifyUserList:''
      }
    },
    mounted() {
      this.bankCreateSelect();// sirve para el breadcums console.log(this.$route.path.split('/'))

    },
    methods: {
      TagifyInput() {
        // setTimeout(() => {
        
        //   let valueInArray = JSON.parse(document.querySelector('#TagifyUserList').value)
        //   this.$emit("change",valueInArray);
        // }, 100);
        setTimeout(() => {           
            let valueInArray ={}
            if(this.$refs.bankSel.value.length < 30){
              document.querySelector('#TagifyUserList').value = "";
              return;
            }
            
            try {
              valueInArray = JSON.parse(document.querySelector('#TagifyUserList').value)
            }catch (error) {
              throw console.error('FAIL');
            }
          // let valueInArray = JSON.parse(document.querySelector('#TagifyUserList').value)
          this.$emit("change",valueInArray);
        }, 200);
      },
      tagTemplate(tagData) {
        document.querySelector('#TagifyUserList').value = tagData.value;
        setTimeout(() => {
          document.querySelectorAll('.tagify__dropdown__footer')[0].textContent ='Solo puede selecionar un banco.'
        }, 100);
        return `
                    <tag title="${tagData.name}"
                      contenteditable='false'
                      spellcheck='false'
                      tabIndex="-1"
                      class="tagify__tag"
                      value="${tagData.value}"
                      name="${tagData.name}"
                      logo="${tagData.logo}"
                      
                    >
                      <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                      <div>
                        <div class='tagify__tag__avatar-wrap'>
                          <img onerror="this.style.visibility='hidden'" src="${tagData.logo}">
                        </div>
                        <span class='tagify__tag-text'>${tagData.name}</span>
                      </div>
                    </tag>
                  `;
      },

      suggestionItemTemplate(tagData) {
        setTimeout(() => {
          document.querySelectorAll('.tagify__dropdown__footer')[0].textContent = document.querySelectorAll('.tagify__tag-text').length > 0 ?
            'Solo puede selecionar un banco.':
            `${this.banks.length - 10} bancos más, usa el buscador.`
            
        }, 50);
        if (document.querySelector('#TagifyUserList').value.length < 1) {
          return `
              <div 
                value="${tagData.value}" name="${tagData.name}" logo="${tagData.logo}"  mappedvalue="${tagData.mappedValue}"
                class='tagify__dropdown__item align-items-center ${tagData.class ? tagData.class : ''}'
                tabindex="0"
                role="option"
              >
                ${tagData.logo
              ? `<div class='tagify__dropdown__item__avatar-wrap'>
                    <img onerror="this.style.visibility='hidden'" src="${tagData.logo}">
                  </div>`
              : ''
            }
                <strong>${tagData.name}</strong>
              </div>`;
        }
      },
      bankCreateSelect() {
        const TagifyUserListEl = document.querySelector('#TagifyUserList');
        setTimeout(() => {


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
              searchKeys: ['name'] // very important to set by which keys to search for suggesttions when typing
            },
            templates: {
              tag: this.tagTemplate,
              dropdownItem: this.suggestionItemTemplate
            },
            whitelist: this.banks
          });
        }, 500);
      },
    }
  };
  </script>
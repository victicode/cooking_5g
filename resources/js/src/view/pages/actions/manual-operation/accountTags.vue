<template>
    <div class="col-lg-12 col-mg-12 col-sm-12 mb-3 ">
      <label><strong>{{ labelTag }}</strong></label>
      <input
        :name="idTag"
        class="form-control tagify_convert"
        :ref="idTag"
        value=""
        disabled
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
      name: "accountTagss",
      props: ['accounts','labelTag','idTag'],
      data() {
        return {
          TagifyUserList:''
        }
      },
      watch: {
        'accounts': {
            handler: function (after, before) {
            this.bankCreateSelect();
            },
            deep: true
        }
      },
      mounted() {
          this.bankCreateSelect();// sirve para el breadcums console.log(this.$route.path.split('/'))
      },
      methods: {
        TagifyInput() {
          setTimeout(() => {
            let valueInArray = {}
            try {
              valueInArray = JSON.parse(this.$refs[this.idTag].value)
            } catch (error) {
            }
            this.$emit("change",valueInArray);
          }, 300);
        },
        tagTemplate(tagData) {

          if (this.$refs[this.idTag].value.length < 1) {
            
            this.$refs[this.idTag].value = tagData.value;

            document.querySelector('[name="'+this.idTag+'"]').value = this.$refs[this.idTag].value;
            if (this.accounts[0].business_name == undefined) {
              return `
                      <tag title="${tagData.name}"
                        contenteditable='false'
                        spellcheck='false'
                        tabIndex="-1"
                        class="tagify__tag"
                        value="${tagData.value}"
                        name="${tagData.name}"
  
                        
                      >
                        <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                        <div class="d-flex align-items-center">
                          <div class="avatar me-1">
                            <div  class="avatar avatar-online">
                              ${ tagData.name.charAt(0) }
                            </div>
                          </div>
                          <span class='tagify__tag-text'>${tagData.name} ${tagData.surname}</span>
                        </div>
                      </tag>
                    `;
            } else {
              return `
                      <tag title="${tagData.business_name}"
                        contenteditable='false'
                        spellcheck='false'
                        tabIndex="-1"
                        class="tagify__tag"
                        value="${tagData.value}"
                        name="${tagData.business_name}"
                        ruc="${tagData.ruc}"
                        
                      >
                        <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                        <div class="d-flex align-items-center">
                          <div class="avatar me-1">
                            <div  class="avatar avatar-online">
                              ${ tagData.business_name.charAt(0) }
                            </div>
                          </div>
                          <span class='tagify__tag-text'>${tagData.business_name} | ruc: ${tagData.ruc}</span>
                        </div>
                      </tag>
                    `;
            }
          }
        },
  
        suggestionItemTemplate(tagData) {
          // console.log(this.$refs[this.idTag].value.length)

          if (this.$refs[this.idTag].value.length < 1) {
            if(this.accounts[0].business_name == undefined){
              return `
                  <div 
                    value="${tagData.value}" name="${tagData.name}"   mappedvalue="${tagData.mappedValue}"
                    class='tagify__dropdown__item align-items-center ${tagData.class ? tagData.class : ''}'
                    tabindex="0"
                    role="option"
                  >
                  <div class="avatar me-1">
                  <div  class="avatar avatar-online">
                    ${ tagData.name.charAt(0) }
                    </div>
                  </div>
                
                    <strong>${tagData.name} ${tagData.surname} </strong>
                  </div>`;
            } else {
              return `
                  <div 
                    value="${tagData.value}" name="${tagData.business_name}"  ruc="${tagData.ruc}" mappedvalue="${tagData.mappedValue}"
                    class='tagify__dropdown__item align-items-center ${tagData.class ? tagData.class : ''}'
                    tabindex="0"
                    role="option"
                  >
                  <div class="avatar me-1">
                  <div  class="avatar avatar-online">
                    ${ tagData.business_name.charAt(0) }
                    </div>
                  </div>
                
                    <strong>${tagData.business_name} | ruc: ${tagData.ruc} </strong>
                  </div>`;
            }
          }
        },
        bankCreateSelect() {
          const TagifyUserListEl = this.$refs[this.idTag];

          
          setTimeout(() => {
            this.$refs[this.idTag].removeAttribute('disabled')
            let searches = this.accounts[0].business_name == undefined ? 'name' : 'business_name'
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
                searchKeys: [searches] // very important to set by which keys to search for suggesttions when typing
              },
              templates: {
                tag: this.tagTemplate,
                dropdownItem: this.suggestionItemTemplate
              },
              whitelist: this.accounts
            });
            
          }, 200);
        },
      }
    };
    </script>
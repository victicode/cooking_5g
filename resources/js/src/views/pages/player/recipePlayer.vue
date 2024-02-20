<script setup>
import moment from 'moment';
import * as bootstrap from 'bootstrap'

const props = defineProps({
  video: String,


})
</script>
<template class="d-block">
  <VRow 
  class="ma-0 pa-0  mt-4 align-center" 
  >
    <VCol cols="12" class="form-group">
      <video controls >
        <source  type="video/mp4" :src="video" />
      </video>
    </VCol>
  </VRow>
</template>
<style>
  video{
    border-radius: 5px;
    box-shadow: #898989 0px 5px 19px 0px;
    padding: 5px;
    background: #cb800c;
  }
</style>
<script>
export default {

  data: () => ({
    modal: '',
    isValidateSteps:false,
    isValidateVideoUpload:false,
    forms:'',
    video:null
  }),
  methods:{
    addVideo(id){
      this.video = this.$refs.recipe_video.files[0]
      this.validatorVideoUpload()
    },
    addNewStepRecipe(){
      let newStep = {
        title:'',
        description:''
      }

      this.preparation.push(newStep)
    },
  },
  mounted(){
  }


};

</script>





















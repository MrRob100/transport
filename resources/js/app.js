import './bootstrap';
import { createApp } from 'vue';
import BusTimes from './components/BusTimes.vue';
import TrainTimes from './components/TrainTimes.vue';

const app = createApp({
  template: `
    <div>
      <BusTimes />
      <TrainTimes />
    </div>
  `,
  components: {
    BusTimes,
    TrainTimes
  }
});

app.mount('#app');

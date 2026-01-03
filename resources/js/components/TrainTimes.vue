<template>
  <div class="container">
    <h1>Train</h1>
    <div class="departures">
      <div v-if="loading" class="loading">
        Loading train times...
      </div>
      <div v-else-if="error" class="error">
        {{ error }}
      </div>
      <div v-else v-html="trainTimesHtml"></div>
    </div>
    <p><small>Data scraped from <a href="https://www.realtimetrains.co.uk/search/simple/gb-nr:NLS/to/gb-nr:BRI" target="_blank">realtimetrains.co.uk</a></small></p>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'TrainTimes',
  data() {
    return {
      trainTimesHtml: '',
      loading: true,
      error: null,
      refreshInterval: null
    }
  },
  async mounted() {
    await this.fetchTrainTimes()
    this.startAutoRefresh()
  },
  beforeUnmount() {
    this.stopAutoRefresh()
  },
  methods: {
    async fetchTrainTimes() {
      try {
        this.loading = true
        this.error = null

        const response = await axios.get('/api/train-times')

        if (response.data.success) {
          this.trainTimesHtml = response.data.data
        } else {
          this.error = response.data.error || 'Failed to fetch train times'
        }
      } catch (err) {
        this.error = err.response?.data?.error || 'Network error occurred'
      } finally {
        this.loading = false
      }
    },
    startAutoRefresh() {
      this.refreshInterval = setInterval(() => {
        this.fetchTrainTimes()
      }, 60000) // Refresh every 60 seconds (1 minute)
    },
    stopAutoRefresh() {
      if (this.refreshInterval) {
        clearInterval(this.refreshInterval)
        this.refreshInterval = null
      }
    }
  }
}
</script>
<style>
.servicelist {
    height: 400px;
    overflow-y: scroll;
}
</style>
<style scoped>
.container {
  max-width: none !important;
  margin: 10px 0 0 0 !important;
  background: transparent !important;
  padding: 0 !important;
  border-radius: 0 !important;
  box-shadow: none !important;
  color: #ff6600 !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-weight: 800 !important;
  text-transform: uppercase !important;
}

h1 {
  color: #ff6600 !important;
  font-size: 48px !important;
  text-align: center !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-weight: 800 !important;
  text-transform: uppercase !important;
  letter-spacing: 3px !important;
}

.loading {
  text-align: center !important;
  padding: 20px !important;
  color: #ff6600 !important;
  font-size: 36px !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-weight: 800 !important;
  text-transform: uppercase !important;
}

.error {
  color: #ff0000 !important;
  padding: 10px !important;
  background-color: #330000 !important;
  border: 3px solid #ff0000 !important;
  border-radius: 0 !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-weight: 800 !important;
  text-transform: uppercase !important;
  font-size: 24px !important;
}

:deep(table) {
  width: 100% !important;
  border-collapse: collapse !important;
  margin-top: 20px !important;
  border: 3px solid #ff6600 !important;
  background: transparent !important;
}

/* Default smaller text for all train content */
:deep(*) {
  font-size: 16px !important;
}

:deep(th), :deep(td) {
  padding: 10px !important;
  text-align: left !important;
  border: 2px solid #ff6600 !important;
  color: #ff6600 !important;
  background: transparent !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-weight: 800 !important;
  text-transform: uppercase !important;
  font-size: 16px !important;
}

/* Make .time divs specifically large */
:deep(.time) {
  font-size: 48px !important;
  font-weight: 800 !important;
  display: block !important;
  margin: 10px 0 !important;
}

/* Keep other train service info smaller */
:deep(.destination), :deep(.platform), :deep(.operator) {
  font-size: 14px !important;
}

:deep(th) {
  background-color: transparent !important;
  font-weight: 800 !important;
  color: #ff6600 !important;
  border-bottom: 4px solid #ff6600 !important;
}

:deep(tr:hover) {
  background-color: #111111 !important;
}

:deep(a) {
  color: #ff8800 !important;
  text-decoration: none !important;
  font-weight: 800 !important;
}

:deep(a:hover) {
  color: #ffaa00 !important;
}

:deep(p), :deep(small) {
  color: #ff6600 !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-weight: 800 !important;
  text-transform: uppercase !important;
  text-align: center !important;
  margin-top: 15px !important;
  font-size: 18px !important;
}

:deep(.service) {
  font-size: 32px !important;
  font-weight: 800 !important;
  display: block !important;
  margin: 10px 0 !important;
  padding: 10px !important;
  border: 2px solid #ff6600 !important;
}

:deep(hr) {
  border: 2px solid #ff6600 !important;
  margin: 15px 0 !important;
}
</style>

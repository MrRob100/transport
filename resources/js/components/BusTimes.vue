<template>
  <div class="container">
    <h1>Bus Times - Stop 0190NSC30636</h1>
    <div class="departures">
      <div v-if="loading" class="loading">
        Loading bus times...
      </div>
      <div v-else-if="error" class="error">
        {{ error }}
      </div>
      <div v-else v-html="departuresHtml"></div>
    </div>
    <p><small>Data scraped from <a href="https://bustimes.org/stops/0190NSC30636" target="_blank">bustimes.org</a></small></p>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'BusTimes',
  data() {
    return {
      departuresHtml: '',
      loading: true,
      error: null,
      refreshInterval: null
    }
  },
  async mounted() {
    await this.fetchBusTimes()
    this.startAutoRefresh()
  },
  beforeUnmount() {
    this.stopAutoRefresh()
  },
  methods: {
    async fetchBusTimes() {
      try {
        this.loading = true
        this.error = null

        const response = await axios.get('/api/bus-times')

        if (response.data.success) {
          this.departuresHtml = response.data.data
        } else {
          this.error = response.data.error || 'Failed to fetch bus times'
        }
      } catch (err) {
        this.error = err.response?.data?.error || 'Network error occurred'
      } finally {
        this.loading = false
      }
    },
    startAutoRefresh() {
      this.refreshInterval = setInterval(() => {
        this.fetchBusTimes()
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
.aside, #departures {
    height: 400px;
    overflow-y: scroll;
}
</style>

<style scoped>

.container {
  max-width: none !important;
  margin: 0 !important;
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
  border-bottom: 4px solid #ff6600 !important;
  padding-bottom: 20px !important;
  margin-bottom: 40px !important;
  font-size: 48px !important;
  text-align: center !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-weight: 800 !important;
  text-transform: uppercase !important;
  letter-spacing: 3px !important;
}

.departures {
  margin-top: 40px !important;
}

.loading {
  text-align: center !important;
  padding: 40px !important;
  color: #ff6600 !important;
  font-size: 36px !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-weight: 800 !important;
  text-transform: uppercase !important;
}

.error {
  color: #ff0000 !important;
  padding: 20px !important;
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

:deep(th), :deep(td) {
  padding: 20px !important;
  text-align: left !important;
  border: 2px solid #ff6600 !important;
  color: #ff6600 !important;
  background: transparent !important;
  font-family: 'JetBrains Mono', monospace !important;
  font-weight: 800 !important;
  text-transform: uppercase !important;
  font-size: 24px !important;
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
  margin-top: 40px !important;
  font-size: 18px !important;
}

:deep(.service) {
  font-size: 32px !important;
  font-weight: 800 !important;
  display: block !important;
  margin: 20px 0 !important;
  padding: 20px !important;
  border: 2px solid #ff6600 !important;
}

:deep(hr) {
  border: 2px solid #ff6600 !important;
  margin: 30px 0 !important;
}

</style>

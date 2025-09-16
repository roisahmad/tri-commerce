export const useToast = () => {
  const showToast = (message: string, type: 'success' | 'error' | 'info' = 'info') => {
    const toast = document.createElement('div')
    toast.className = `toast-notification fixed top-4 right-4 z-50 px-4 py-2 rounded-md text-white font-medium transform translate-x-full transition-transform duration-300 ${
      type === 'success' ? 'bg-green-500' : 
      type === 'error' ? 'bg-red-500' : 
      'bg-blue-500'
    }`
    toast.textContent = message
    
    document.body.appendChild(toast)
    
    // Animate in
    setTimeout(() => {
      toast.classList.remove('translate-x-full')
    }, 100)
    
    // Animate out and remove
    setTimeout(() => {
      toast.classList.add('translate-x-full')
      setTimeout(() => {
        document.body.removeChild(toast)
      }, 300)
    }, 3000)
  }
  
  return { showToast }
}
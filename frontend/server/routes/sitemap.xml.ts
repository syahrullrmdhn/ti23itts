type SitemapItem = {
  loc: string
  lastmod?: string | null
  changefreq?: 'daily' | 'weekly' | 'monthly' | 'yearly'
  priority?: number
}

const escapeXml = (value: string) => value
  .replaceAll('&', '&amp;')
  .replaceAll('<', '&lt;')
  .replaceAll('>', '&gt;')
  .replaceAll('"', '&quot;')
  .replaceAll("'", '&apos;')

export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig(event)
  const siteUrl = String(config.public.siteUrl).replace(/\/$/, '')
  const apiBase = String(config.public.apiBase).replace(/\/$/, '')
  const urls: SitemapItem[] = [
    { loc: siteUrl, changefreq: 'weekly', priority: 1 },
    { loc: `${siteUrl}/cerita`, changefreq: 'weekly', priority: 0.9 },
  ]

  const [postsResult, studentsResult, episodesResult] = await Promise.allSettled([
    $fetch<any[]>(`${apiBase}/posts`),
    $fetch<any[]>(`${apiBase}/students`),
    $fetch<any[]>(`${apiBase}/episodes`),
  ])

  if (postsResult.status === 'fulfilled') {
    for (const post of postsResult.value) {
      if (!post.slug) continue
      urls.push({
        loc: `${siteUrl}/cerita/${post.slug}`,
        lastmod: post.updated_at || post.published_at,
        changefreq: 'monthly',
        priority: 0.8,
      })
    }
  }

  if (studentsResult.status === 'fulfilled') {
    for (const student of studentsResult.value) {
      if (!student.id) continue
      urls.push({
        loc: `${siteUrl}/crew/${student.id}`,
        lastmod: student.updated_at,
        changefreq: 'monthly',
        priority: 0.7,
      })
    }
  }

  if (episodesResult.status === 'fulfilled') {
    for (const episode of episodesResult.value) {
      if (!episode.id) continue
      urls.push({
        loc: `${siteUrl}/episodes/${episode.id}`,
        lastmod: episode.updated_at,
        changefreq: 'monthly',
        priority: 0.8,
      })
    }
  }

  const body = urls.map((url) => {
    const lastmod = url.lastmod ? `<lastmod>${escapeXml(new Date(url.lastmod).toISOString())}</lastmod>` : ''
    const changefreq = url.changefreq ? `<changefreq>${url.changefreq}</changefreq>` : ''
    const priority = url.priority !== undefined ? `<priority>${url.priority.toFixed(1)}</priority>` : ''
    return `<url><loc>${escapeXml(url.loc)}</loc>${lastmod}${changefreq}${priority}</url>`
  }).join('')

  setHeader(event, 'content-type', 'application/xml; charset=utf-8')
  setHeader(event, 'cache-control', 'public, max-age=3600, s-maxage=3600')

  return `<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">${body}</urlset>`
})

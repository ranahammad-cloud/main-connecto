const interviewers = [
  {
    id: 1,
    name: 'Maya Chen',
    title: 'Senior Product Manager',
    skills: ['Product', 'Strategy', 'Behavioral'],
    price: 35,
    rating: 4.9,
    sessions: 184,
    next: 'Today, 6:00 PM',
    bio: 'Former marketplace PM helping candidates answer ambiguity, prioritization, and leadership prompts.'
  },
  {
    id: 2,
    name: 'Andre Taylor',
    title: 'Staff Backend Engineer',
    skills: ['System Design', 'Laravel', 'APIs'],
    price: 45,
    rating: 4.8,
    sessions: 121,
    next: 'Tomorrow, 10:30 AM',
    bio: 'Designs high-throughput APIs and coaches candidates on architecture, tradeoffs, and scalability.'
  },
  {
    id: 3,
    name: 'Priya Nair',
    title: 'Talent Partner',
    skills: ['Resume', 'Recruiter Screen', 'Leadership'],
    price: 25,
    rating: 5.0,
    sessions: 96,
    next: 'Friday, 2:00 PM',
    bio: 'Recruiting leader specializing in resume positioning, phone screens, and behavioral storytelling.'
  },
  {
    id: 4,
    name: 'Diego Morales',
    title: 'Principal Frontend Engineer',
    skills: ['React', 'UI Systems', 'Portfolio'],
    price: 40,
    rating: 4.9,
    sessions: 142,
    next: 'Monday, 9:00 AM',
    bio: 'Frontend systems expert who runs realistic UI architecture and product sense interviews.'
  }
];

const roleContent = {
  Interviewee: {
    eyebrow: 'Interviewee dashboard',
    title: 'Your practice command center',
    metrics: [
      ['Resume', 'Uploaded and ready'],
      ['Target role', 'Backend Engineer'],
      ['Upcoming', '2 accepted sessions'],
      ['Reviews left', '1 pending review']
    ]
  },
  Interviewer: {
    eyebrow: 'Interviewer dashboard',
    title: 'Manage requests, earnings, and feedback',
    metrics: [
      ['Pending requests', '7 awaiting response'],
      ['Wallet balance', '$1,240 available'],
      ['Acceptance rate', '96% this month'],
      ['Feedback drafts', '3 to submit']
    ]
  },
  Admin: {
    eyebrow: 'Admin dashboard',
    title: 'Operate trust, safety, and transactions',
    metrics: [
      ['Approvals', '18 interviewers pending'],
      ['Disputes', '4 cases open'],
      ['Transactions', '312 payment logs'],
      ['Sessions', '89 monitored today']
    ]
  }
};

const cards = document.querySelector('#interviewerCards');
const searchInput = document.querySelector('#searchInput');
const priceFilter = document.querySelector('#priceFilter');
const roleSelect = document.querySelector('#roleSelect');
const roleEyebrow = document.querySelector('#roleEyebrow');
const roleTitle = document.querySelector('#roleTitle');
const roleMetrics = document.querySelector('#roleMetrics');
const modal = document.querySelector('#bookingModal');
const modalName = document.querySelector('#modalName');
const modalPrice = document.querySelector('#modalPrice');
const modalFee = document.querySelector('#modalFee');
const modalPayout = document.querySelector('#modalPayout');
const themeToggle = document.querySelector('#themeToggle');

function dollars(value) {
  return `$${value.toFixed(2)}`;
}

function initials(name) {
  return name.split(' ').map((part) => part[0]).join('');
}

function openBooking(person) {
  const fee = person.price * 0.10;
  modalName.textContent = person.name;
  modalPrice.textContent = dollars(person.price);
  modalFee.textContent = dollars(fee);
  modalPayout.textContent = dollars(person.price - fee);
  if (typeof modal.showModal === 'function') {
    modal.showModal();
  } else {
    alert(`Booking ${person.name}: ${dollars(person.price)} session, ${dollars(fee)} Connecto commission.`);
  }
}

function renderCards() {
  const query = searchInput.value.trim().toLowerCase();
  const maxPrice = Number(priceFilter.value);
  const visible = interviewers.filter((person) => {
    const haystack = `${person.name} ${person.title} ${person.skills.join(' ')} ${person.bio}`.toLowerCase();
    return haystack.includes(query) && person.price <= maxPrice;
  });

  cards.innerHTML = '';
  if (!visible.length) {
    cards.innerHTML = '<div class="no-results">No interviewers match those filters. Try a broader search.</div>';
    return;
  }

  visible.forEach((person) => {
    const article = document.createElement('article');
    article.className = 'card';
    article.innerHTML = `
      <div class="avatar">${initials(person.name)}</div>
      <h3>${person.name}</h3>
      <p><strong>${person.title}</strong></p>
      <p>${person.bio}</p>
      <div class="chips">${person.skills.map((skill) => `<span>${skill}</span>`).join('')}</div>
      <div class="card-footer"><span>⭐ ${person.rating} · ${person.sessions} sessions</span><strong>$${person.price}</strong></div>
      <button class="button" type="button">Book ${person.next}</button>
    `;
    article.querySelector('button').addEventListener('click', () => openBooking(person));
    cards.appendChild(article);
  });
}

function renderRole(role) {
  const content = roleContent[role];
  roleEyebrow.textContent = content.eyebrow;
  roleTitle.textContent = content.title;
  roleMetrics.innerHTML = content.metrics.map(([label, value]) => `<div><span>${label}</span><strong>${value}</strong></div>`).join('');
}

function setTheme(nextTheme) {
  document.documentElement.dataset.theme = nextTheme;
  localStorage.setItem('connecto-theme', nextTheme);
  themeToggle.textContent = nextTheme === 'dark' ? '☀️ Light' : '🌙 Dark';
}

searchInput.addEventListener('input', renderCards);
priceFilter.addEventListener('change', renderCards);
roleSelect.addEventListener('change', (event) => renderRole(event.target.value));
document.querySelector('#openBookingHero').addEventListener('click', () => openBooking(interviewers[0]));
themeToggle.addEventListener('click', () => setTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark'));

setTheme(localStorage.getItem('connecto-theme') || 'light');
renderRole(roleSelect.value);
renderCards();

import React, { useMemo, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { Calendar, CheckCircle2, CreditCard, ShieldCheck, Star, Video, Wallet } from 'lucide-react';
import { bookings, interviewers } from './data/mockData';
import './styles.css';

const roles = ['Interviewee', 'Interviewer', 'Admin'];

function App() {
  const [role, setRole] = useState('Interviewee');
  const [query, setQuery] = useState('');
  const filtered = useMemo(() => interviewers.filter((person) => `${person.name} ${person.title} ${person.skills.join(' ')}`.toLowerCase().includes(query.toLowerCase())), [query]);

  return <main>
    <Nav role={role} setRole={setRole} />
    <Hero />
    <section className="shell grid-two" id="marketplace">
      <div>
        <p className="eyebrow">Marketplace</p>
        <h2>Find vetted experts for realistic mock interviews.</h2>
        <div className="search"><input value={query} onChange={(event) => setQuery(event.target.value)} placeholder="Search by skill, company level, or role" /><button>Filter</button></div>
        <div className="cards">{filtered.map((person) => <InterviewerCard key={person.id} person={person} />)}</div>
      </div>
      <Dashboard role={role} />
    </section>
    <section className="shell flow" id="booking"><Step icon={<Calendar />} title="Calendar booking" text="Interviewees pick an available slot and receive instant confirmation after interviewer approval." /><Step icon={<CreditCard />} title="Stripe escrow" text="Payments are authorized up front, held safely, and released after completion with a 10% Connecto commission." /><Step icon={<Video />} title="Video room" text="Agora/100ms-ready session rooms include chat, a timer, feedback notes, and post-session reviews." /></section>
    <section className="shell admin" id="admin"><h2>Admin operations center</h2><p>Approve interviewers, monitor sessions, view transaction logs, and resolve disputes from one responsive panel.</p><div className="metrics"><Metric label="Pending approvals" value="18" /><Metric label="Escrow balance" value="$8.4k" /><Metric label="Avg. rating" value="4.9" /></div></section>
  </main>;
}

function Nav({ role, setRole }) {
  return <header className="nav shell"><strong>Connecto</strong><nav><a href="#marketplace">Marketplace</a><a href="#booking">Booking</a><a href="#admin">Admin</a></nav><select aria-label="Role" value={role} onChange={(event) => setRole(event.target.value)}>{roles.map((item) => <option key={item}>{item}</option>)}</select></header>;
}

function Hero() {
  return <section className="hero shell"><div><p className="eyebrow">Mock interviews, real momentum</p><h1>Book expert interview practice and convert feedback into offers.</h1><p>Connecto brings interviewees, professional interviewers, payments, video sessions, wallets, reviews, and admin governance into a clean SaaS marketplace.</p><div className="actions"><button>Start practicing</button><button className="ghost">Become an interviewer</button></div></div><div className="hero-card"><ShieldCheck /><h3>Production-ready MVP scope</h3><ul><li>Email, Google, and LinkedIn auth</li><li>Role-based dashboards</li><li>Stripe payments with escrow</li><li>Laravel REST API + MySQL schema</li></ul></div></section>;
}

function InterviewerCard({ person }) {
  return <article className="card"><div className={`avatar ${person.accent}`}>{person.name.split(' ').map((name) => name[0]).join('')}</div><div><h3>{person.name}</h3><p>{person.title}</p><div className="chips">{person.skills.map((skill) => <span key={skill}>{skill}</span>)}</div><footer><span><Star /> {person.rating} · {person.sessions} sessions</span><strong>${person.price}</strong></footer><button className="wide">Book {person.next}</button></div></article>;
}

function Dashboard({ role }) {
  const copy = { Interviewee: ['Resume uploaded', '3 target roles', '2 upcoming sessions'], Interviewer: ['$1,240 wallet', '7 pending requests', '96% acceptance'], Admin: ['18 approvals', '4 disputes', '312 transactions'] };
  return <aside className="dashboard"><p className="eyebrow">{role} dashboard</p><h2>Role-aware workspace</h2>{copy[role].map((item) => <div className="dash-row" key={item}><CheckCircle2 /> <span>{item}</span></div>)}<div className="booking-list">{bookings.map((booking) => <div key={booking.expert}><span>{booking.expert}</span><small>{booking.status} · {booking.date}</small><b>{booking.price}</b></div>)}</div><button className="wide"><Wallet /> Open wallet</button></aside>;
}

function Step({ icon, title, text }) { return <article><div className="step-icon">{icon}</div><h3>{title}</h3><p>{text}</p></article>; }
function Metric({ label, value }) { return <div><strong>{value}</strong><span>{label}</span></div>; }

createRoot(document.getElementById('root')).render(<App />);

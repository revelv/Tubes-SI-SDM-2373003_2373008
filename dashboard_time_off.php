<?php
require 'process.php';
require 'auth.php';
include 'admin_header.php';
redirectIfNotLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Off</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4a6bff;
            --bg: #f9fafb;
            --white: #fff;
            --text: #333;
            --light: #777;
            --border: #e0e0e0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }
        
        body {
            background: var(--bg);
            color: var(--text);
            display: grid;
            grid-template-columns: 200px 1fr;
            min-height: 100vh;
            margin-top: 50px;
        }
        
        /* Top Bar (replaces header) */
        .top-bar {
            grid-column: 1/-1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: var(--white);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .search {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            width: 250px;
        }
        
        .search i { color: var(--light); margin-right: 0.5rem; }
        .search input { border: none; background: transparent; width: 100%; }
        
        .date-nav {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .date-nav button {
            background: #f5f7ff;
            border: none;
            padding: 0.5rem;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        /* Sidebar */
        navi {
            background: var(--white);
            border-right: 1px solid var(--border);
            padding: 1rem 0;
        }
        
        .navi ul { list-style: none; }
        
        .navi li {
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            cursor: pointer;
            border-left: 3px solid transparent;
        }
        
        .navi li:hover { background: #f0f2ff; }
        .navi li.active { background: #e0e6ff; border-left: 3px solid var(--primary); color: var(--primary); }
        
        /* Main Content */
        main {
            padding: 1.5rem;
            overflow-y: auto;
        }
        
        h2 { margin-bottom: 1rem; }
        
        .calendars {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .month {
            background: var(--white);
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .month h3 { margin-bottom: 0.8rem; font-size: 1rem; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        
        th { color: var(--light); font-weight: normal; padding: 0.3rem; }
        td { text-align: center; padding: 0.3rem; border: 1px solid var(--border); }
        td:first-child { border: none; color: var(--light); text-align: right; }
        td.selected { background: var(--primary); color: white; font-weight: bold; }
        
        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .top-bar { flex-direction: column; gap: 1rem; }
            .search { width: 100%; }
            .navi { display: none; }
        }
    </style>
</head>
<body>
    <!-- Replaced header with div.top-bar -->
    <div class="top-bar">
        <h1>Time Off Calendar</h1>
        <div class="search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search...">
        </div>
        <div class="date-nav">
            <button id="prev-year"><i class="fas fa-chevron-left"></i> Year</button>
            <button id="today">Today</button>
            <span id="current-year">2025</span>
            <button id="next-year">Year <i class="fas fa-chevron-right"></i></button>
        </div>
    </div>

    <nav class="navi">
        <ul>
            <li class="active"><i class="far fa-calendar-alt"></i> Time Off</li>
            <li><i class="far fa-user"></i> My Time</li>
            <li><i class="fas fa-chart-pie"></i> Overview</li>
            <li><i class="fas fa-users"></i> Management</li>
            <li><i class="fas fa-chart-bar"></i> Reporting</li>
            <li><i class="fas fa-cog"></i> Configuration</li>
        </ul>
    </nav>

    <main>
        <section>
            <h2>Pending Requests</h2>
            <div class="calendars">
                <div class="month">
                    <h3>January 2025</h3>
                    <table>
                        <tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr>
                        <tr><td>1</td><td></td><td></td><td>1</td><td>2</td><td>3</td><td>4</td></tr>
                        <tr><td>2</td><td>5</td><td>6</td><td>7</td><td>8</td><td>9</td><td>10</td></tr>
                        <tr><td>3</td><td>12</td><td>13</td><td>14</td><td>15</td><td>16</td><td>17</td></tr>
                        <tr><td>4</td><td>19</td><td>20</td><td>21</td><td>22</td><td>23</td><td>24</td></tr>
                        <tr><td>5</td><td>26</td><td>27</td><td>28</td><td>29</td><td>30</td><td>31</td></tr>
                    </table>
                </div>
                
                <div class="month">
                    <h3>February 2025</h3>
                    <table>
                        <tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr>
                        <tr><td></td><td>5</td><td></td><td></td><td></td><td>1</td><td></td></tr>
                        <tr><td>6</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td></tr>
                        <tr><td>7</td><td>9</td><td>10</td><td>11</td><td>12</td><td>13</td><td>14</td></tr>
                        <tr><td>8</td><td>16</td><td>17</td><td>18</td><td>19</td><td>20</td><td>21</td></tr>
                        <tr><td>9</td><td>23</td><td>24</td><td>25</td><td>26</td><td>27</td><td>28</td></tr>
                    </table>
                </div>
            </div>
        </section>

        <section>
            <h2>March 2025</h2>
            <div class="calendars">
                <div class="month">
                    <h3>March 2025</h3>
                    <table>
                        <tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr>
                        <tr><td>9</td><td></td><td></td><td></td><td></td><td>1</td><td></td></tr>
                        <tr><td>10</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td></tr>
                        <tr><td>11</td><td>9</td><td>10</td><td>11</td><td>12</td><td>13</td><td>14</td></tr>
                        <tr><td>12</td><td>16</td><td>17</td><td>18</td><td>19</td><td>20</td><td>21</td></tr>
                        <tr><td>13</td><td>23</td><td>24</td><td>25</td><td>26</td><td>27</td><td>28</td></tr>
                    </table>
                </div>
                
                <div class="month">
                    <h3>April 2025</h3>
                    <table>
                        <tr><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr>
                        <tr><td>14</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td></td></tr>
                        <tr><td>15</td><td>6</td><td>7</td><td>8</td><td>9</td><td>10</td><td>11</td></tr>
                        <tr><td>16</td><td>13</td><td>14</td><td>15</td><td>16</td><td>17</td><td>18</td></tr>
                        <tr><td>17</td><td>20</td><td>21</td><td>22</td><td>23</td><td>24</td><td>25</td></tr>
                        <tr><td>18</td><td>27</td><td>28</td><td>29</td><td>30</td><td></td><td></td></tr>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Year navigation
            const yearEl = document.getElementById('current-year');
            let year = 2025;
            
            document.getElementById('prev-year').addEventListener('click', () => {
                yearEl.textContent = --year;
            });
            
            document.getElementById('next-year').addEventListener('click', () => {
                yearEl.textContent = ++year;
            });
            
            document.getElementById('today').addEventListener('click', () => {
                year = new Date().getFullYear();
                yearEl.textContent = year;
            });
            
            // Calendar selection
            document.querySelectorAll('.month td').forEach(td => {
                if(!td.textContent.trim()) return;
                
                td.addEventListener('click', () => {
                    document.querySelectorAll('.month td.selected').forEach(el => {
                        el.classList.remove('selected');
                    });
                    td.classList.add('selected');
                });
            });
            
            // Sidebar navigation
            document.querySelectorAll('nav li').forEach(li => {
                li.addEventListener('click', () => {
                    document.querySelector('nav li.active').classList.remove('active');
                    li.classList.add('active');
                });
            });
            
            // Search
            document.querySelector('.search input').addEventListener('keyup', (e) => {
                if(e.key === 'Enter') {
                    console.log('Search:', e.target.value);
                }
            });
        });
    </script>
</body>
</html>
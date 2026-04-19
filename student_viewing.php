<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOAU | Student Activity View</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bsu-green: #2d6a4f;
            --bsu-dark: #1b4332;
            --bsu-mint: #d8f3dc;
            --text-dark: #1a1c1e;
            --text-muted: #5f6368;
            --border-color: #dadce0;
            --bg-page: #f0f2f5;
            --white: #ffffff;
        }

        /* Reduced Top Spacing */
        body { 
            font-family: 'Inter', -apple-system, sans-serif; 
            background-color: var(--bg-page); 
            color: var(--text-dark); 
            margin: 0; 
            padding: 20px; /* Reduced from 40px */
            line-height: 1.5; 
        }

        .container { max-width: 1200px; margin: 20px auto 0; } /* Reduced top margin */

        /* Header Section */
        .view-header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .view-header h2 { 
            margin: 0; 
            color: var(--bsu-dark); 
            font-size: 1.4rem;
            font-weight: 700;
        }

        /* Search Bar */
        .universal-search {
            width: 100%;
            max-width: 350px;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.85rem;
            background: var(--white);
        }

        /* Table Wrapper */
        .table-wrapper {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
            overflow-x: auto;
            border: 1px solid var(--border-color);
        }

        table { width: 100%; border-collapse: collapse; min-width: 2600px; }

        th { 
            background: #f8f9fa; 
            padding: 14px 12px; 
            font-size: 0.65rem; 
            text-transform: uppercase;
            font-weight: 700;
            border-bottom: 2px solid var(--bsu-mint);
            text-align: left;
            color: var(--bsu-dark);
            position: sticky; top: 0;
            white-space: nowrap;
        }

        td { 
            padding: 12px;
            border-bottom: 1px solid #f0f0f0; 
            background: var(--white);
            font-size: 0.8rem;
            color: var(--text-dark);
        }

        tr:hover td { background-color: #f9fbf9; }

        /* Column Specifics */
        .col-org { min-width: 200px; font-weight: 700; color: var(--bsu-green); }
        .col-title { min-width: 350px; font-weight: 600; }
        .col-remarks { min-width: 300px; color: var(--text-muted); }
        .col-data { text-align: center; }

    </style>
</head>
<body>

<?php if(file_exists("navbar.php")) include "navbar.php"; ?>

<div class="container">
    <div class="view-header">
        <h2>Organization Activity List</h2>
        <input type="text" id="tableSearch" class="universal-search" placeholder="Search activities..." onkeyup="filterTable()">
    </div>

    <div class="table-wrapper">
        <table id="soauTable">
            <thead>
                <tr>
                    <th>Permit ID</th>
                    <th class="col-org">Organization</th>
                    <th class="col-title">Activity Title</th>
                    <th>Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Venue</th>
                    <th>Appr. Date</th>
                    <th>Report Due</th>
                    <th>Actual Sub.</th>
                    <th>Rating %</th>
                    <th>AP +/-</th>
                    <th>AR +/-</th>
                    <th class="col-remarks">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>08-0114</td>
                    <td class="col-org">IT SOCIETY</td>
                    <td class="col-title">CodeQuest: Debugging Challenge 2026</td>
                    <td>Seminar / Training</td>
                    <td>2026-03-20</td>
                    <td>2026-03-21</td>
                    <td>08:00 AM</td>
                    <td>05:00 PM</td>
                    <td>ICT Hall</td>
                    <td>2026-03-10</td>
                    <td>2026-03-28</td>
                    <td>2026-03-25</td>
                    <td>95%</td>
                    <td>+10</td>
                    <td>+5</td>
                    <td class="col-remarks">High student engagement recorded.</td>
                </tr>
                <tr>
                    <td>08-0117</td>
                    <td class="col-org">STUDENT COUNCIL</td>
                    <td class="col-title">Leadership Summit: Empowering Voices</td>
                    <td>Meeting / Fellowship</td>
                    <td>2026-05-12</td>
                    <td>2026-05-14</td>
                    <td>09:00 AM</td>
                    <td>04:00 PM</td>
                    <td>Multi-Purpose Hall</td>
                    <td>2026-04-15</td>
                    <td>2026-05-21</td>
                    <td>Pending</td>
                    <td>-</td>
                    <td>0</td>
                    <td>0</td>
                    <td class="col-remarks">Venue confirmed.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function filterTable() {
        const input = document.getElementById("tableSearch");
        const filter = input.value.toLowerCase();
        const tr = document.querySelectorAll("#soauTable tbody tr");
        tr.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    }
</script>

</body>
</html>
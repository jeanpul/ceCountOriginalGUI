/**
 * @file Calendar.js
 * @author Fabien.Pelisson@blueeyevideo.com
 */

function dateChanged(id, calendar) 
{
  var y = calendar.date.getFullYear();
  var m = calendar.date.getMonth() + 1;     // integer, 0..11
  var d = calendar.date.getDate();      // integer, 1..31
  var dt = new Date(y,m-1,d,0,0,0);
  var dateValue = formatDate(dt, 'yyyy-MM-dd');
  changeIndicatorParameter(id, "DateValue", dateValue);
  updateIndicatorData(id);
};

/**
 * Do not close the calendar when user
 * select a date.
 * This is a copy of the function onSelect
 * where the call to the close function was removed
 * @see jscalendar/calendar-setup.js
 */
function dateSelected(cal) 
{
  var p = cal.params;
  var update = (cal.dateClicked || p.electric);
  if (update && p.inputField) {
    p.inputField.value = cal.date.print(p.ifFormat);
    if (typeof p.inputField.onchange == "function")
      p.inputField.onchange();
  }
  if (update && p.displayArea)
    p.displayArea.innerHTML = cal.date.print(p.daFormat);
  if (update && typeof p.onUpdate == "function")
    p.onUpdate(cal);
  if (update && p.flat) {
    if (typeof p.flatCallback == "function")
      p.flatCallback(cal);
  }
};

function InitCalendar(id)
{
    Calendar.setup(
    {
      inputField: "DateValue_" + id,
      displayArea: "DateValueDisplayed_" + id,
      ifFormat: "%Y-%m-%d",
      daFormat: "%Y-%m-%d",
      button: "trigger_" + id,
      electric: false, 
      onUpdate: function (calendar) { dateChanged(id, calendar); },
      onSelect: dateSelected    
    } );
}

function InitPeriod(id)
{
    Calendar.setup(
    {
      inputField: "DateValue_from_" + id,
      displayArea: "DateValueDisplayed_from_" + id,
      ifFormat: "%Y-%m-%d",
      daFormat: "%Y-%m-%d",
      button: "trigger_from_" + id,
      electric: false, 
      onUpdate: function (calendar) { PeriodChanged(id, calendar, "from"); },
      onSelect: dateSelected    
    } );

    Calendar.setup(
    {
      inputField: "DateValue_to_" + id,
      displayArea: "DateValueDisplayed_to_" + id,
      ifFormat: "%Y-%m-%d",
      daFormat: "%Y-%m-%d",
      button: "trigger_to_" + id,
      electric: false, 
      onUpdate: function (calendar) { PeriodChanged(id, calendar, "to"); },
      onSelect: dateSelected    
    } );
}

function PeriodChanged(id, calendar, periodType) 
{
  var y = calendar.date.getFullYear();
  var m = calendar.date.getMonth() + 1;     // integer, 0..11
  var d = calendar.date.getDate();      // integer, 1..31
  var dt = new Date(y,m-1,d,0,0,0);
  var dateValue = formatDate(dt, 'yyyy-MM-dd');
  changeIndicatorParameter(id, "DateValue_" + periodType, dateValue);
  updateIndicatorData(id);
};

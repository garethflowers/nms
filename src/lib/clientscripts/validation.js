function ValidateElementPass(element)
{
    $(element).removeClass('error');
}

function ValidateElementFail(element)
{
    $(element).addClass('error');
}
